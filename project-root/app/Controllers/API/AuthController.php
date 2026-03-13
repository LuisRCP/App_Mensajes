<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\PersonaModel;
use App\Models\UsuarioModel;

class AuthController extends BaseController
{
    protected $personaModel;
    protected $usuarioModel;

    public function __construct()
    {
        $this->personaModel = new PersonaModel();
        $this->usuarioModel = new UsuarioModel();
    }

    public function register()
    {
        $data = $this->request->getJSON(true);

        if (
            empty($data['nombre']) ||
            empty($data['apellido_paterno']) ||
            empty($data['apellido_materno']) ||
            empty($data['correo'])
        ) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos'
            ]);
        }

        // verificar si el correo ya existe
        $existente = $this->usuarioModel->obtenerPorCorreo($data['correo']);

        if ($existente) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El correo ya está registrado'
            ]);
        }

        // crear persona
        $personaId = $this->personaModel->crearPersona([
            'persona_Nombre' => $data['nombre'],
            'persona_ApellidoPaterno' => $data['apellido_paterno'],
            'persona_ApellidoMaterno' => $data['apellido_materno']
        ]);

        // generar contraseña automática
        $passwordPlain = bin2hex(random_bytes(4));

        $passwordHash = password_hash(
            $passwordPlain,
            PASSWORD_DEFAULT
        );

        // crear usuario
        $this->usuarioModel->crearUsuario([
            'usuario_Correo' => $data['correo'],
            'usuario_Clave' => $passwordHash,
            'personaId' => $personaId
        ]);

        // enviar correo
        $email = \Config\Services::email();

        $email->setTo($data['correo']);
        $email->setSubject('Tu acceso al Chat');

        $email->setMessage("
            <h3>Bienvenido al Chat</h3>
            <p>Tu contraseña es:</p>
            <h2>{$passwordPlain}</h2>
            <p>Inicia sesión y cámbiala después.</p>
        ");

        $email->send();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Usuario creado. Revisa tu correo.'
        ]);
    }

    public function login()
    {
        $data = $this->request->getJSON(true);

        if (empty($data['correo']) || empty($data['password'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos incompletos'
            ]);
        }

        $usuario = $this->usuarioModel->validarLogin(
            $data['correo'],
            $data['password']
        );

        if (!$usuario) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON([
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ]);
        }

        // crear sesión
        session()->set([
            'usuarioId' => $usuario['usuarioId'],
            'logged_in' => true
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Login correcto',
            'usuarioId' => $usuario['usuarioId']
        ]);
    }

    public function logout()
    {
        session()->destroy();

        return $this->response->setJSON([
            'success' => true
        ]);
    }

    public function usuarios()
    {
        $userId = session()->get('usuarioId');

        $usuarios = $this->usuarioModel->listarUsuarios();

        // excluir usuario actual
        $usuarios = array_filter($usuarios, function ($u) use ($userId) {
            return $u['usuarioId'] != $userId;
        });

        return $this->response->setJSON([
            'usuarios' => array_values($usuarios)
        ]);
    }
}