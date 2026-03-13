<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\ConversacionModel;
use App\Models\MensajeModel;
use App\Models\ArchivoModel;

class ChatController extends BaseController
{
    protected $convModel;
    protected $msgModel;
    protected $archivoModel;

    public function __construct()
    {
        helper('crypto');

        $this->convModel = new ConversacionModel();
        $this->msgModel  = new MensajeModel();
        $this->archivoModel = new ArchivoModel();
    }


    public function send()
    {
        $userId = session()->get('usuarioId');

        $data = $this->request->getJSON(true);

        $receptorId = $data['receptor_id'];
        $mensajePlano = $data['mensaje'];

        $convId = $this->convModel->obtenerOCrearConversacion($userId, $receptorId);

        $mensajeCifrado = encryptMessage($mensajePlano);

        $mensajeId = $this->msgModel->crearMensaje([
            'conversacionId' => $convId,
            'emisor_id' => $userId,
            'mensaje_contenido' => $mensajeCifrado,
            'tipo' => 'texto'
        ]);

        $this->convModel->actualizarUltimoMensaje($convId, $mensajeId);

        // =====================
        // RESPUESTA IA
        // =====================

        $IA_ID = 3;

        if ($receptorId == $IA_ID) {

            $ai = new \App\Libraries\OpenAIService();

            $respuesta = $ai->preguntar($mensajePlano);

            $respuestaCifrada = encryptMessage($respuesta);

            $mensajeIA = $this->msgModel->crearMensaje([
                'conversacionId' => $convId,
                'emisor_id' => $IA_ID,
                'mensaje_contenido' => $respuestaCifrada,
                'tipo' => 'texto'
            ]);

            $this->convModel->actualizarUltimoMensaje($convId, $mensajeIA);
        }

        return $this->response->setJSON([
            'success' => true
        ]);
    }

    public function conversation($receptorId)
    {
        $userId = session()->get('usuarioId');

        $conv = $this->convModel->obtenerConversacion($userId, $receptorId);

        if (!$conv) {
            return $this->response->setJSON([
                'mensajes' => []
            ]);
        }

        $mensajes = $this->msgModel->obtenerMensajesConversacion($conv['conversacionId']);

        foreach ($mensajes as &$m) {

            if ($m['tipo'] === 'texto') {
                $m['mensaje_contenido'] = decryptMessage($m['mensaje_contenido']);
            }

        }

        return $this->response->setJSON([
            'mensajes' => $mensajes
        ]);
    }

    public function conversaciones()
    {
        $userId = session()->get('usuarioId');

        $rows = $this->convModel->obtenerConversacionesUsuario($userId);

        foreach ($rows as &$r) {

            if ($r['mensaje_contenido']) {
                $r['mensaje_contenido'] = decryptMessage($r['mensaje_contenido']);
            }

        }

        return $this->response->setJSON([
            'conversaciones' => $rows
        ]);
    }

    public function sendFile()
    {
        $userId = session()->get('usuarioId');

        $receptorId = $this->request->getPost('receptor_id');

        $file = $this->request->getFile('archivo');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Archivo inválido'
            ]);
        }

        $maxSize = 15 * 1024 * 1024;

        if ($file->getSize() > $maxSize) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Archivo demasiado grande'
            ]);
        }

        $mime = $file->getMimeType();
        $size = $file->getSize();
        $nombreOriginal = $file->getClientName();
        $extension = $file->getExtension();

        $nombreCifrado = bin2hex(random_bytes(16)) . '.' . $extension;

        $uploadPath = FCPATH . 'uploads/chat/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $file->move($uploadPath, $nombreCifrado);

        $archivoId = $this->archivoModel->guardarArchivo([
            'nombre_original' => $nombreOriginal,
            'nombre_cifrado' => $nombreCifrado,
            'ruta_archivo' => 'uploads/chat/' . $nombreCifrado,
            'mime_type' => $mime,
            'tamaño_bytes' => $size
        ]);

        $convId = $this->convModel->obtenerOCrearConversacion($userId, $receptorId);

        if (str_starts_with($mime, 'image/')) {
            $tipo = 'imagen';
        } elseif (str_starts_with($mime, 'video/')) {
            $tipo = 'video';
        } elseif (str_starts_with($mime, 'audio/')) {
            $tipo = 'audio';
        } else {
            $tipo = 'archivo';
        }

        $mensajeId = $this->msgModel->crearMensaje([
            'conversacionId' => $convId,
            'emisor_id' => $userId,
            'tipo' => $tipo,
            'archivoId' => $archivoId,
            'mensaje_contenido' => ''
        ]);

        $this->convModel->actualizarUltimoMensaje($convId, $mensajeId);

        return $this->response->setJSON([
            'success' => true
        ]);
    }

    public function subirAvatar()
    {
        $userId = session()->get('usuarioId');

        $file = $this->request->getFile('avatar');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false
            ]);
        }

        $nombre = 'avatar_' . $userId . '.' . $file->getExtension();

        $path = FCPATH . 'uploads/avatars/';

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $file->move($path, $nombre, true);

        $this->usuarioModel->update($userId, [
            'usuario_avatar' => 'uploads/avatars/' . $nombre
        ]);

        return $this->response->setJSON([
            'success' => true,
            'avatar' => 'uploads/avatars/' . $nombre
        ]);
    }
}