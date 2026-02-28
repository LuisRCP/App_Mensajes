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
}