<?php

namespace App\Models;
use CodeIgniter\Model;

class ConversacionModel extends Model
{
    protected $table = 'conversacion';
    protected $primaryKey = 'conversacionId';

    protected $allowedFields = [
        'usuario1_id',
        'usuario2_id',
        'ultimo_mensaje_id',
        'actualizado_en'
    ];

    protected $returnType = 'array';
}