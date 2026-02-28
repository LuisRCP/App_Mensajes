<?php

namespace App\Models;
use CodeIgniter\Model;

class MensajeModel extends Model
{
    protected $table = 'mensaje';
    protected $primaryKey = 'mensajeId';

    protected $allowedFields = [
        'conversacionId',
        'emisor_id',
        'mensaje_contenido',
        'tipo',
        'archivoId'
    ];

    protected $returnType = 'array';
}