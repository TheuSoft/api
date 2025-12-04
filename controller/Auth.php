<?php
namespace controller;

use service\UsuarioService;

class Auth
{
    private $usuarios;

    public function __construct()
    {
        $this->usuarios = new UsuarioService();
    }

    public function login($email, $senha)
    {
        if (empty($email) || empty($senha)) {
            return ["erro" => "Email e senha são obrigatórios"];
        }

        $token = $this->usuarios->autenticar($email, $senha);

        if ($token) {
            return ["token" => $token];
        }

        return ["erro" => "Não autorizado"];
    }
}
