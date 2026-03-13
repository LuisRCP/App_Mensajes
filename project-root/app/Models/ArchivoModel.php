<?php

namespace App\Models;

use CodeIgniter\Model;

class ArchivoModel extends Model
{
    protected $table            = 'archivo';
    protected $primaryKey       = 'archivoId';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields    = true;

    protected $allowedFields = [
        'nombre_original',
        'nombre_cifrado',
        'ruta_archivo',
        'mime_type',
        'tamaño_bytes',
        'fecha_subida'
    ];

    protected $useTimestamps = false;

    public function guardarArchivo(array $data)
    {
        $this->insert($data);

        return $this->getInsertID();
    }

    public function obtenerArchivo($archivoId)
    {
        return $this->where('archivoId', $archivoId)->first();
    }

    public function eliminarArchivo($archivoId)
    {
        return $this->delete($archivoId);
    }

    public function obtenerArchivosConversacion($conversacionId)
    {
        return $this->select('archivo.*')
            ->join('mensaje','mensaje.archivoId = archivo.archivoId')
            ->where('mensaje.conversacionId', $conversacionId)
            ->orderBy('archivo.fecha_subida','DESC')
            ->findAll();
    }

}