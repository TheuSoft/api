<?php
namespace controller;

use service\UsuarioService;

class Usuario
{
    private $service;

    public function __construct()
    {
        $this->service = new UsuarioService();
    }

    public function listar()
    {
        return $this->service->listarUsuario();
    }

    public function buscar($id = null)
    {
        if ($id) {
            return $this->service->listarId($id);
        }
        return ["erro" => "ID não fornecido"];
    }

    public function cadastrar($nome, $email, $senha)
    {
        if (empty($nome) || empty($email) || empty($senha)) {
            return ["erro" => "Nome, email e senha são obrigatórios"];
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["erro" => "Email inválido"];
        }

        $usuarioExistente = $this->service->buscarPorEmail($email);
        if ($usuarioExistente) {
            return ["erro" => "Email já cadastrado"];
        }

        $resultado = $this->service->cadastrarComSenha($nome, $email, $senha);
        return ["mensagem" => $resultado];
    }

    public function inserir($nome, $email)
    {
        if (empty($nome) || empty($email)) {
            return ["erro" => "Campos obrigatórios: nome, email"];
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["erro" => "Email inválido"];
        }

        $resultado = $this->service->inserir($nome, $email);
        return ["mensagem" => $resultado];
    }

    public function alterar($id, $nome, $email)
    {
        if (empty($id) || empty($nome) || empty($email)) {
            return ["erro" => "Campos obrigatórios: id, nome, email"];
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["erro" => "Email inválido"];
        }

        $resultado = $this->service->alterar($id, $nome, $email);
        return ["mensagem" => $resultado];
    }

    public function deletar($id)
    {
        if (empty($id)) {
            return ["erro" => "ID é obrigatório"];
        }

        $ok = $this->service->deletar($id);
        if ($ok) {
            return ["mensagem" => "Usuário deletado com sucesso"];
        }
        return ["erro" => "Registro não encontrado ou não excluído"];
    }
}
