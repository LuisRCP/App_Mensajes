<?php

namespace App\Models;

use CodeIgniter\Model;

class ConversacionModel extends Model
{
    protected $table            = 'conversacion';
    protected $primaryKey       = 'conversacionId';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'usuario1_id',
        'usuario2_id',
        'ultimo_mensaje_id',
        'actualizado_en'
    ];

    protected $useTimestamps = false;

    public function obtenerConversacion($user1, $user2)
    {
        $u1 = min($user1, $user2);
        $u2 = max($user1, $user2);

        return $this->where('usuario1_id', $u1)
                    ->where('usuario2_id', $u2)
                    ->first();
    }

    public function crearConversacion($user1, $user2)
    {
        $u1 = min($user1, $user2);
        $u2 = max($user1, $user2);

        $this->insert([
            'usuario1_id' => $u1,
            'usuario2_id' => $u2,
            'actualizado_en' => date('Y-m-d H:i:s')
        ]);

        return $this->getInsertID();
    }

    public function obtenerOCrearConversacion($user1, $user2)
    {
        $conv = $this->obtenerConversacion($user1, $user2);

        if ($conv) {
            return $conv['conversacionId'];
        }

        return $this->crearConversacion($user1, $user2);
    }

    public function actualizarUltimoMensaje($convId, $mensajeId)
    {
        return $this->update($convId, [
            'ultimo_mensaje_id' => $mensajeId,
            'actualizado_en' => date('Y-m-d H:i:s')
        ]);
    }

    public function obtenerConversacionesUsuario($userId)
    {
        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT 
                u.usuarioId,
                p.persona_Nombre,
                p.persona_ApellidoPaterno,
                c.conversacionId,
                m.mensaje_contenido,
                m.tipo,
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

        return $query->getResultArray();
    }

}