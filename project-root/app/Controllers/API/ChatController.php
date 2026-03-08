<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\ConversacionModel;
use App\Models\MensajeModel;

class ChatController extends BaseController
{
    protected $convModel;
    protected $msgModel;

    public function __construct()
    {
        helper('crypto');

        $this->convModel = new ConversacionModel();
        $this->msgModel  = new MensajeModel();
    }

    public function send()
    {
        $userId = session()->get('usuarioId');

        $data = $this->request->getJSON(true);

        $receptorId = $data['receptor_id'];
        $mensajePlano = $data['mensaje'];

        // ordenar usuarios (evita duplicados)
        $u1 = min($userId, $receptorId);
        $u2 = max($userId, $receptorId);

        // buscar conversación
        $conv = $this->convModel
            ->where('usuario1_id', $u1)
            ->where('usuario2_id', $u2)
            ->first();

        if (!$conv) {
            $convId = $this->convModel->insert([
                'usuario1_id' => $u1,
                'usuario2_id' => $u2,
                'actualizado_en' => date('Y-m-d H:i:s')
            ]);
        } else {
            $convId = $conv['conversacionId'];
        }

        // cifrar mensaje
        $mensajeCifrado = encryptMessage($mensajePlano);

        // guardar mensaje
        $mensajeId = $this->msgModel->insert([
            'conversacionId' => $convId,
            'emisor_id' => $userId,
            'mensaje_contenido' => $mensajeCifrado,
            'tipo' => 'texto'
        ]);

        // actualizar conversación
        $this->convModel->update($convId, [
            'ultimo_mensaje_id' => $mensajeId,
            'actualizado_en' => date('Y-m-d H:i:s')
        ]);

        // =====================
        // RESPUESTA IA
        // =====================

        $IA_ID = 3;

        if($receptorId == $IA_ID){

            $ai = new \App\Libraries\OpenAIService();

            $respuesta = $ai->preguntar($mensajePlano);

            $respuestaCifrada = encryptMessage($respuesta);

            $mensajeIA = $this->msgModel->insert([
                'conversacionId' => $convId,
                'emisor_id' => $IA_ID,
                'mensaje_contenido' => $respuestaCifrada,
                'tipo' => 'texto'
            ]);

            $this->convModel->update($convId, [
                'ultimo_mensaje_id' => $mensajeIA,
                'actualizado_en' => date('Y-m-d H:i:s')
            ]);
        }
        return $this->response->setJSON([
            'success' => true
        ]);
    }

    public function conversation($receptorId)
    {
        $userId = session()->get('usuarioId');

        // ordenar usuarios igual que en send()
        $u1 = min($userId, $receptorId);
        $u2 = max($userId, $receptorId);

        // buscar conversación
        $conv = $this->convModel
            ->where('usuario1_id', $u1)
            ->where('usuario2_id', $u2)
            ->first();

        if(!$conv){
            return $this->response->setJSON([
                'mensajes'=>[]
            ]);
        }

        $mensajes = $this->msgModel
            ->select('mensaje.*, archivo.ruta_archivo')
            ->join('archivo','archivo.archivoId = mensaje.archivoId','left')
            ->where('mensaje.conversacionId', $conv['conversacionId'])
            ->orderBy('mensaje.fecha_enviado','ASC')
            ->findAll();

        // descifrar mensajes
        foreach($mensajes as &$m){

            if($m['tipo'] === 'texto'){
                $m['mensaje_contenido'] = decryptMessage($m['mensaje_contenido']);
            }

        }

        return $this->response->setJSON([
            'mensajes'=>$mensajes
        ]);
    }

    public function conversaciones()
    {
        $userId = session()->get('usuarioId');

        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT 
                u.usuarioId,
                p.persona_Nombre,
                p.persona_ApellidoPaterno,
                c.conversacionId,
                m.mensaje_contenido,
                m.fecha_enviado
            FROM usuario u
            JOIN persona p 
                ON p.personaId = u.personaId

            LEFT JOIN conversacion c 
                ON (
                    (c.usuario1_id = u.usuarioId AND c.usuario2_id = ?)
                    OR
                    (c.usuario2_id = u.usuarioId AND c.usuario1_id = ?)
                )

            LEFT JOIN mensaje m 
                ON m.mensajeId = c.ultimo_mensaje_id

            WHERE u.usuarioId != ?

            ORDER BY c.actualizado_en DESC, p.persona_Nombre ASC
        ", [$userId, $userId, $userId]);

        $rows = $query->getResultArray();

        foreach ($rows as &$r) {
            if ($r['mensaje_contenido']) {
                $r['mensaje_contenido'] = decryptMessage($r['mensaje_contenido']);
            }
        }

        return $this->response->setJSON([
            'conversaciones' => $rows
        ]);
    }

    public function sendImage()
    {
        $userId = session()->get('usuarioId');

        $receptorId = $this->request->getPost('receptor_id');

        if ($receptorId == $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No puedes enviarte mensajes'
            ]);
        }

        $file = $this->request->getFile('imagen');

        if (!$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Archivo inválido'
            ]);
        }

        // generar nombre cifrado
        $nombreCifrado = bin2hex(random_bytes(16)) . '.' . $file->getExtension();

        $file->move(FCPATH . 'uploads/chat', $nombreCifrado);

        // guardar archivo en BD
        $db = \Config\Database::connect();

        $db->table('archivo')->insert([
            'nombre_original' => $file->getClientName(),
            'nombre_cifrado' => $nombreCifrado,
            'ruta_archivo' => 'uploads/chat/' . $nombreCifrado,
            'mime_type' => $file->getMimeType(),
            'tamaño_bytes' => $file->getSize()
        ]);

        $archivoId = $db->insertID();
        // ordenar usuarios
        $u1 = min($userId, $receptorId);
        $u2 = max($userId, $receptorId);

        $conv = $this->convModel
            ->where('usuario1_id', $u1)
            ->where('usuario2_id', $u2)
            ->first();

        if (!$conv) {

            $convId = $this->convModel->insert([
                'usuario1_id' => $u1,
                'usuario2_id' => $u2,
                'actualizado_en' => date('Y-m-d H:i:s')
            ]);

        } else {

            $convId = $conv['conversacionId'];

        }

        $mensajeId = $this->msgModel->insert([
            'conversacionId' => $convId,
            'emisor_id' => $userId,
            'tipo' => 'imagen',
            'archivoId' => $archivoId,
            'mensaje_contenido' => ''
        ]);

        return $this->response->setJSON([
            'success' => true
        ]);
    }
}