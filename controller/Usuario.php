<?php
namespace controller;

use service\UsuarioService;

class Usuario
{
    public function listar()
    {
        $usuario = new UsuarioService();
        return $usuario->listarUsuario();
    }

    public function cadastrar($nome, $email, $senha)
    {
        $usuario = new UsuarioService();
        return $usuario->cadastrarComSenha($nome, $email, $senha);
    }

    public function listarId($id)
    {
        $usuario = new UsuarioService();
        return $usuario->listarId($id);
    }

    public function alterar($id, $nome, $email, $senha = null)
    {
        $usuario = new UsuarioService();
        return $usuario->alterar($id, $nome, $email, $senha);
    }

    public function deletar($id)
    {
        $usuario = new UsuarioService();
        return $usuario->deletar($id);
    }
}
