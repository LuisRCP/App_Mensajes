package com.example.myapplication.activities

import android.os.Bundle
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import com.example.myapplication.R
import com.example.myapplication.api.ApiClient
import com.example.myapplication.api.AuthApi
import com.example.myapplication.api.RegisterRequest
import kotlinx.coroutines.launch

class RegisterActivity : AppCompatActivity() {

    private lateinit var nombreInput: EditText
    private lateinit var apellidoPInput: EditText
    private lateinit var apellidoMInput: EditText
    private lateinit var correoInput: EditText
    private lateinit var btnRegister: Button
    private lateinit var progress: ProgressBar

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_register)

        nombreInput = findViewById(R.id.inputNombre)
        apellidoPInput = findViewById(R.id.inputApellidoP)
        apellidoMInput = findViewById(R.id.inputApellidoM)
        correoInput = findViewById(R.id.inputCorreo)
        btnRegister = findViewById(R.id.btnRegister)
        progress = findViewById(R.id.progressRegister)

        btnRegister.setOnClickListener {

            val nombre = nombreInput.text.toString()
            val apellidoP = apellidoPInput.text.toString()
            val apellidoM = apellidoMInput.text.toString()
            val correo = correoInput.text.toString()

            if (nombre.isEmpty() || apellidoP.isEmpty() || apellidoM.isEmpty() || correo.isEmpty()) {

                Toast.makeText(this, "Completa todos los campos", Toast.LENGTH_SHORT).show()
                return@setOnClickListener

            }

            registrar(nombre, apellidoP, apellidoM, correo)

        }

    }

    private fun registrar(nombre: String, apellidoP: String, apellidoM: String, correo: String) {

        progress.visibility = ProgressBar.VISIBLE

        val authApi = ApiClient.retrofit.create(AuthApi::class.java)

        lifecycleScope.launch {

            try {

                val response = authApi.register(
                    RegisterRequest(
                        nombre,
                        apellidoP,
                        apellidoM,
                        correo
                    )
                )

                progress.visibility = ProgressBar.GONE

                if (response.isSuccessful && response.body()?.success == true) {

                    Toast.makeText(
                        this@RegisterActivity,
                        "Usuario creado. Revisa tu correo.",
                        Toast.LENGTH_LONG
                    ).show()

                    finish()

                } else {

                    Toast.makeText(
                        this@RegisterActivity,
                        response.body()?.message ?: "Error al registrar",
                        Toast.LENGTH_LONG
                    ).show()

                }

            } catch (e: Exception) {

                progress.visibility = ProgressBar.GONE

                Toast.makeText(
                    this@RegisterActivity,
                    "Error de conexión",
                    Toast.LENGTH_LONG
                ).show()

            }

        }

    }

}