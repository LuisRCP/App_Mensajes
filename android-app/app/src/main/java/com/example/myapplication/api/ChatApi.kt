package com.example.myapplication.api

import com.example.myapplication.model.Conversation
import com.example.myapplication.model.Message
import com.example.myapplication.model.User
import retrofit2.Response
import retrofit2.http.*

data class SendMessageRequest(
    val receptor_id: Int,
    val mensaje: String
)

data class SendMessageResponse(
    val success: Boolean
)

data class ConversationsResponse(
    val conversaciones: List<Conversation>
)

data class MessagesResponse(
    val mensajes: List<Message>
)

data class UsersResponse(
    val usuarios: List<User>
)

interface ChatApi {

    @GET("api/usuarios")
    suspend fun usuarios(): Response<UsersResponse>


    @GET("api/chat/conversaciones")
    suspend fun conversaciones(): Response<ConversationsResponse>


    @GET("api/chat/conversation/{id}")
    suspend fun conversation(
        @Path("id") receptorId: Int
    ): Response<MessagesResponse>


    @POST("api/chat/send")
    suspend fun sendMessage(
        @Body request: SendMessageRequest
    ): Response<SendMessageResponse>

}