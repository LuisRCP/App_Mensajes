package com.example.myapplication.api

import com.example.myapplication.model.User
import com.example.myapplication.model.UserResponse
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.POST

data class LoginRequest(
    val correo: String,
    val password: String
)

data class RegisterRequest(
    val nombre: String,
    val apellido_paterno: String,
    val apellido_materno: String,
    val correo: String
)

data class LoginResponse(
    val success: Boolean,
    val message: String,
    val usuarioId: Int?
)

data class RegisterResponse(
    val success: Boolean,
    val message: String
)

interface AuthApi {

    @POST("api/auth/login")
    suspend fun login(
        @Body request: LoginRequest
    ): Response<LoginResponse>


    @POST("api/auth/register")
    suspend fun register(
        @Body request: RegisterRequest
    ): Response<RegisterResponse>

    @GET("api/usuarios")
    suspend fun getUsuarios(): Response<UserResponse>

}