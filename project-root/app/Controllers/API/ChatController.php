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
            ->where('conversacionId', $conv['conversacionId'])
            ->orderBy('fecha_enviado','ASC')
            ->findAll();

        // descifrar mensajes
        foreach($mensajes as &$m){
            $m['mensaje_contenido'] = decryptMessage($m['mensaje_contenido']);
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
                c.conversacionId,
                u.usuarioId,
                p.persona_Nombre,
                p.persona_ApellidoPaterno,
                m.mensaje_contenido,
                m.fecha_enviado
            FROM conversacion c
            JOIN usuario u 
                ON (u.usuarioId = IF(c.usuario1_id = ?, c.usuario2_id, c.usuario1_id))
            JOIN persona p 
                ON p.personaId = u.personaId
            LEFT JOIN mensaje m 
                ON m.mensajeId = c.ultimo_mensaje_id
            WHERE c.usuario1_id = ? OR c.usuario2_id = ?
            ORDER BY c.actualizado_en DESC
        ", [$userId, $userId, $userId]);

        $rows = $query->getResultArray();

        // descifrar último mensaje
        foreach ($rows as &$r) {
            if ($r['mensaje_contenido']) {
                $r['mensaje_contenido'] = decryptMessage($r['mensaje_contenido']);
            }
        }

        return $this->response->setJSON([
            'conversaciones' => $rows
        ]);
    }
}