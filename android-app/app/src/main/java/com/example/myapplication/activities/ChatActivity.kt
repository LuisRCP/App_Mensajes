package com.example.myapplication.activities

import android.os.Bundle
import android.util.Log
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import com.example.myapplication.R
import com.example.myapplication.api.*
import kotlinx.coroutines.launch
import androidx.drawerlayout.widget.DrawerLayout
import androidx.appcompat.app.ActionBarDrawerToggle
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.myapplication.adapter.MessageAdapter
import com.example.myapplication.adapter.UserAdapter
import com.example.myapplication.model.Message
import com.example.myapplication.model.User

class ChatActivity : AppCompatActivity() {

    private lateinit var inputMensaje: EditText
    private lateinit var btnEnviar: Button

    private lateinit var recyclerMensajes: RecyclerView
    private lateinit var recyclerUsuarios: RecyclerView

    private lateinit var adapterMensajes: MessageAdapter
    private lateinit var adapterUsuarios: UserAdapter

    private val mensajes = mutableListOf<Message>()
    private val usuarios = mutableListOf<User>()

    private var receptorId: Int = 0
    private var miUsuarioId: Int = 0

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_chat)

        Log.d("CHAT_API", "ChatActivity iniciada")

        inputMensaje = findViewById(R.id.inputMensaje)
        btnEnviar = findViewById(R.id.btnEnviar)

        recyclerMensajes = findViewById(R.id.recyclerMensajes)
        recyclerUsuarios = findViewById(R.id.recyclerUsuarios)

        miUsuarioId = 5

        // ========================
        // MENSAJES
        // ========================

        adapterMensajes = MessageAdapter(mensajes, miUsuarioId)

        recyclerMensajes.layoutManager = LinearLayoutManager(this)
        recyclerMensajes.adapter = adapterMensajes

        // ========================
        // USUARIOS
        // ========================

        adapterUsuarios = UserAdapter(usuarios) { usuario ->

            receptorId = usuario.usuarioId.toInt()

            Log.d("CHAT_API", "Usuario seleccionado: $receptorId")

            cargarMensajes()

        }

        recyclerUsuarios.layoutManager = LinearLayoutManager(this)
        recyclerUsuarios.adapter = adapterUsuarios

        // ========================
        // DRAWER
        // ========================

        val drawerLayout = findViewById<DrawerLayout>(R.id.drawerLayout)
        val toolbar = findViewById<androidx.appcompat.widget.Toolbar>(R.id.toolbar)

        setSupportActionBar(toolbar)

        val toggle = ActionBarDrawerToggle(
            this,
            drawerLayout,
            toolbar,
            R.string.open,
            R.string.close
        )

        drawerLayout.addDrawerListener(toggle)
        toggle.syncState()

        // ========================
        // CARGAR USUARIOS
        // ========================

        cargarUsuarios()

        btnEnviar.setOnClickListener {

            val mensaje = inputMensaje.text.toString().trim()

            if (mensaje.isNotEmpty()) {

                enviarMensaje(mensaje)
                inputMensaje.setText("")

            }

        }

    }

    // ========================
    // CARGAR USUARIOS
    // ========================

    private fun cargarUsuarios() {

        val api = ApiClient.retrofit.create(AuthApi::class.java)

        lifecycleScope.launch {

            try {

                val response = api.getUsuarios()

                if (response.isSuccessful) {

                    val lista = response.body()?.usuarios ?: emptyList()

                    usuarios.clear()
                    usuarios.addAll(lista)

                    adapterUsuarios.notifyDataSetChanged()
                    Log.d("CHAT_API", "Usuarios: $lista")

                }

            } catch (e: Exception) {

                Toast.makeText(
                    this@ChatActivity,
                    "Error cargando usuarios",
                    Toast.LENGTH_SHORT
                ).show()

            }

        }

    }

    // ========================
    // CARGAR MENSAJES
    // ========================

    private fun cargarMensajes() {

        val chatApi = ApiClient.retrofit.create(ChatApi::class.java)

        lifecycleScope.launch {

            try {

                val response = chatApi.conversation(receptorId)

                if (response.isSuccessful) {

                    val lista = response.body()?.mensajes ?: emptyList()

                    mensajes.clear()
                    mensajes.addAll(lista)

                    adapterMensajes.notifyDataSetChanged()

                    recyclerMensajes.scrollToPosition(mensajes.size - 1)

                }

            } catch (e: Exception) {

                Toast.makeText(
                    this@ChatActivity,
                    "Error cargando mensajes",
                    Toast.LENGTH_SHORT
                ).show()

            }

        }

    }

    // ========================
    // ENVIAR MENSAJE
    // ========================

    private fun enviarMensaje(mensaje: String) {

        val chatApi = ApiClient.retrofit.create(ChatApi::class.java)

        lifecycleScope.launch {

            try {

                val response = chatApi.sendMessage(
                    SendMessageRequest(receptorId, mensaje)
                )

                if (response.isSuccessful) {

                    cargarMensajes()

                }

            } catch (e: Exception) {

                Toast.makeText(
                    this@ChatActivity,
                    "Error enviando mensaje",
                    Toast.LENGTH_SHORT
                ).show()

            }

        }

    }

}