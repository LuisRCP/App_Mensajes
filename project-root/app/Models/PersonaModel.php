<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    protected $table            = 'persona';
    protected $primaryKey       = 'personaId';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'persona_Nombre',
        'persona_ApellidoPaterno',
        'persona_ApellidoMaterno'
    ];

    protected $useTimestamps = false;

    public function crearPersona(array $data)
    {
        $this->insert($data);
        return $this->getInsertID();
    }

    public function obtenerPersona($personaId)
    {
        return $this->where('personaId', $personaId)->first();
    }

    public function obtenerNombreCompleto($personaId)
    {
        $persona = $this->obtenerPersona($personaId);

        if (!$persona) {
            return null;
        }

        return $persona['persona_Nombre'] . ' ' .
               $persona['persona_ApellidoPaterno'] . ' ' .
               $persona['persona_ApellidoMaterno'];
    }

    public function buscarPersonas($texto)
    {
        return $this->like('persona_Nombre', $texto)
            ->orLike('persona_ApellidoPaterno', $texto)
            ->orLike('persona_ApellidoMaterno', $texto)
            ->findAll();
    }
}