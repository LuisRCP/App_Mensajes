package com.example.myapplication.model

data class Message(

    val mensajeId: Int?,
    val conversacionId: Int?,
    val emisor_id: Int,
    val tipo: String,
    val mensaje_contenido: String?,
    val archivoId: Int?,
    val ruta_archivo: String?,
    val fecha_envio: String?

)