<?php

namespace App\Controllers;

class ChatPageController extends BaseController
{
    public function login()
    {
        // si ya inició sesión → ir al chat
        if (session()->get('logged_in')) {
            return redirect()->to('/chat');
        }

        return view('auth/login');
    }

    public function chat()
    {
        // proteger vista
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('chat/index', [
            'usuarioId' => session()->get('usuarioId')
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}