<?php

namespace App\Models;
use CodeIgniter\Model;

class PersonaModel extends Model
{
    protected $table = 'persona';
    protected $primaryKey = 'personaId';

    protected $allowedFields = [
        'persona_Nombre',
        'persona_ApellidoPaterno',
        'persona_ApellidoMaterno'
    ];
}