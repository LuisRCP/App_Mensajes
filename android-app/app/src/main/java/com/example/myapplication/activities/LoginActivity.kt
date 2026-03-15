package com.example.myapplication.activities

import android.content.Intent
import android.os.Bundle
import android.widget.*
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import com.example.myapplication.MainActivity
import com.example.myapplication.R
import com.example.myapplication.api.ApiClient
import com.example.myapplication.api.AuthApi
import com.example.myapplication.api.LoginRequest
import kotlinx.coroutines.launch
import kotlin.jvm.java

class LoginActivity : AppCompatActivity() {

    private lateinit var emailInput: EditText
    private lateinit var passwordInput: EditText
    private lateinit var loginButton: Button
    private lateinit var registerText: TextView
    private lateinit var progress: ProgressBar

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        emailInput = findViewById(R.id.inputEmail)
        passwordInput = findViewById(R.id.inputPassword)
        loginButton = findViewById(R.id.btnLogin)
        registerText = findViewById(R.id.textRegister)
        progress = findViewById(R.id.progressLogin)

        loginButton.setOnClickListener {

            val correo = emailInput.text.toString()
            val password = passwordInput.text.toString()

            if (correo.isEmpty() || password.isEmpty()) {
                Toast.makeText(this, "Completa todos los campos", Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            login(correo, password)
        }

        registerText.setOnClickListener {

            startActivity(Intent(this, RegisterActivity::class.java))

        }
    }

    private fun login(correo: String, password: String) {

        progress.visibility = ProgressBar.VISIBLE

        val authApi = ApiClient.retrofit.create(AuthApi::class.java)

        lifecycleScope.launch {

            try {

                val response = authApi.login(
                    LoginRequest(correo, password)
                )

                progress.visibility = ProgressBar.GONE

                if (response.isSuccessful && response.body()?.success == true) {

                    val usuarioId = response.body()?.usuarioId

                    Toast.makeText(
                        this@LoginActivity,
                        "Bienvenido",
                        Toast.LENGTH_SHORT
                    ).show()

                    val intent = Intent(this@LoginActivity, ChatActivity::class.java)
                    intent.putExtra("usuarioId", usuarioId)

                    startActivity(intent)
                    finish()
                } else {

                    Toast.makeText(
                        this@LoginActivity,
                        "Credenciales incorrectas",
                        Toast.LENGTH_SHORT
                    ).show()

                }

            } catch (e: Exception) {

                progress.visibility = ProgressBar.GONE

                Toast.makeText(
                    this@LoginActivity,
                    "Error de conexión",
                    Toast.LENGTH_LONG
                ).show()

            }

        }

    }

}