<?php

namespace App\Models;
use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'usuarioId';

    protected $allowedFields = [
        'usuario_Correo',
        'usuario_Clave',
        'personaId'
    ];

    protected $returnType = 'array';
}