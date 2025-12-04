<?php
namespace controller;

use service\UsuarioService;

class Auth
{
    public function login($email, $senha)
    {
        $usuario = new UsuarioService();
        return $usuario->autenticar($email, $senha);
    }
}
