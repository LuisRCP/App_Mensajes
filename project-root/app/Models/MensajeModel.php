<?php

namespace App\Models;

use CodeIgniter\Model;

class MensajeModel extends Model
{
    protected $table            = 'mensaje';
    protected $primaryKey       = 'mensajeId';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'conversacionId',
        'emisor_id',
        'mensaje_contenido',
        'tipo',
        'archivoId',
        'leido',
        'leido_en'
    ];

    protected $useTimestamps = false;

    public function crearMensaje(array $data)
    {
        $this->insert($data);
        return $this->getInsertID();
    }

    public function obtenerMensajesConversacion($convId)
    {
        return $this->select('mensaje.*, archivo.ruta_archivo, archivo.nombre_original, archivo.mime_type')
            ->join('archivo','archivo.archivoId = mensaje.archivoId','left')
            ->where('mensaje.conversacionId', $convId)
            ->orderBy('mensaje.fecha_enviado','ASC')
            ->findAll();
    }

    public function obtenerMensajesNuevos($convId, $ultimoMensajeId)
    {
        return $this->select('mensaje.*, archivo.ruta_archivo, archivo.nombre_original, archivo.mime_type')
            ->join('archivo','archivo.archivoId = mensaje.archivoId','left')
            ->where('mensaje.conversacionId', $convId)
            ->where('mensaje.mensajeId >', $ultimoMensajeId)
            ->orderBy('mensaje.fecha_enviado','ASC')
            ->findAll();
    }

    public function marcarComoLeidos($convId, $userId)
    {
        return $this->where('conversacionId', $convId)
            ->where('emisor_id !=', $userId)
            ->set([
                'leido' => 1,
                'leido_en' => date('Y-m-d H:i:s')
            ])
            ->update();
    }

    public function obtenerUltimoMensaje($convId)
    {
        return $this->where('conversacionId', $convId)
            ->orderBy('mensajeId', 'DESC')
            ->first();
    }

}