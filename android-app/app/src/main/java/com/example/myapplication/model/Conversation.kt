package com.example.myapplication.model

data class Conversation(
    val conversacionId: Int,
    val usuarioId: Int,
    val persona_Nombre: String,
    val avatar: String?,
    val mensaje_contenido: String?,
    val fecha_ultimo_mensaje: String?
)