<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuario';
    protected $primaryKey       = 'usuarioId';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'usuario_Correo',
        'usuario_Clave',
        'personaId',
        'usuario_avatar'
    ];

    protected $useTimestamps = false;

    public function crearUsuario(array $data)
    {
        $this->insert($data);
        return $this->getInsertID();
    }

    public function obtenerPorCorreo($correo)
    {
        return $this->where('usuario_Correo', $correo)->first();
    }

    public function obtenerUsuarioConPersona($usuarioId)
    {
        return $this->select('
            usuario.*,
            usuario.usuario_avatar,
            persona.persona_Nombre,
            persona.persona_ApellidoPaterno,
            persona.persona_ApellidoMaterno
        ')
        ->join('persona', 'persona.personaId = usuario.personaId')
        ->where('usuario.usuarioId', $usuarioId)
        ->first();
    }

    public function validarLogin($correo, $password)
    {
        $usuario = $this->obtenerPorCorreo($correo);

        if (!$usuario) {
            return false;
        }

        if (!password_verify($password, $usuario['usuario_Clave'])) {
            return false;
        }

        return $usuario;
    }

    public function listarUsuarios()
    {
        return $this->select('
            usuario.usuarioId,
            usuario.usuario_avatar,
            persona.persona_Nombre,
            persona.persona_ApellidoPaterno,
            persona.persona_ApellidoMaterno
        ')
        ->join('persona', 'persona.personaId = usuario.personaId')
        ->orderBy('persona.persona_Nombre', 'ASC')
        ->findAll();
    }
    
    public function actualizarAvatar($usuarioId, $ruta)
    {
        return $this->update($usuarioId, [
            'usuario_avatar' => $ruta
        ]);
    }
}