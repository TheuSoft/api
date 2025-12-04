<?php
namespace service;

use dao\mysql\UsuarioDAO;
use generic\JwtAuth;
use generic\Retorno;
use stdClass;

class UsuarioService extends UsuarioDAO
{
    public function autenticar($email, $senha)
    {
        $retorno = new Retorno();

        // Validacao dos campos obrigatorios
        if (empty($email) || empty($senha)) {
            http_response_code(400);
            $retorno->erro = "Email e senha sao obrigatorios";
            return $retorno;
        }

        // Validacao do formato de email
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            $retorno->erro = "Email invalido";
            return $retorno;
        }

        $rows = parent::verificaLogin($email, $senha);

        if ($rows) {
            $jwt           = new JwtAuth();
            $objeto        = new stdClass();
            $objeto->nome  = $rows[0]["nome"];
            $objeto->email = $rows[0]["email"];

            $token          = $jwt->criarChave(json_encode($objeto));
            $retorno->dados = ["token" => $token];
            return $retorno;
        }

        http_response_code(401);
        $retorno->erro = "Credenciais invalidas";
        return $retorno;
    }

    public function listarUsuario()
    {
        $retorno        = new Retorno();
        $retorno->dados = parent::listar();
        return $retorno;
    }

    public function cadastrarComSenha($nome, $email, $senha)
    {
        $retorno = new Retorno();

        // Validacao dos campos obrigatorios
        if (empty($nome) || empty($email) || empty($senha)) {
            http_response_code(400);
            $retorno->erro = "Todos os campos sao obrigatorios: nome, email e senha";
            return $retorno;
        }

        // Validacao do formato de email
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            $retorno->erro = "Email invalido";
            return $retorno;
        }

        // Validacao da senha (minimo 5 caracteres)
        if (strlen($senha) < 5) {
            http_response_code(400);
            $retorno->erro = "A senha deve ter no minimo 5 caracteres";
            return $retorno;
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql   = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $param = [
            ":nome"  => $nome,
            ":email" => $email,
            ":senha" => $senhaHash,
        ];

        try {
            $this->banco->executar($sql, $param);
            http_response_code(201);
            $retorno->dados = ["mensagem" => "Usuario cadastrado com sucesso"];
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao cadastrar usuario: " . $e->getMessage();
            return $retorno;
        }
    }

    public function listarId($id)
    {
        $retorno = new Retorno();

        // Validacao do ID
        if (empty($id) || ! is_numeric($id)) {
            http_response_code(400);
            $retorno->erro = "ID invalido";
            return $retorno;
        }

        try {
            $resultado = parent::listarId($id);
            if ($resultado) {
                $retorno->dados = $resultado;
                return $retorno;
            }
            http_response_code(404);
            $retorno->erro = "Usuario nao encontrado";
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao buscar usuario: " . $e->getMessage();
            return $retorno;
        }
    }

    public function alterar($id, $nome, $email, $senha = null)
    {
        $retorno = new Retorno();

        // Validacao do ID
        if (empty($id) || ! is_numeric($id)) {
            http_response_code(400);
            $retorno->erro = "ID invalido";
            return $retorno;
        }

        // Validacao dos campos obrigatorios
        if (empty($nome) || empty($email)) {
            http_response_code(400);
            $retorno->erro = "Nome e email sao obrigatorios";
            return $retorno;
        }

        // Validacao do formato de email
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            $retorno->erro = "Email invalido";
            return $retorno;
        }

        // Se a senha foi informada, valida e faz hash
        $senhaHash = null;
        if (! empty($senha)) {
            if (strlen($senha) < 5) {
                http_response_code(400);
                $retorno->erro = "A senha deve ter no minimo 5 caracteres";
                return $retorno;
            }
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        }

        try {
            $sql   = "UPDATE usuarios SET nome = :nome, email = :email";
            $param = [
                ":id"    => $id,
                ":nome"  => $nome,
                ":email" => $email,
            ];

            if (! empty($senha)) {
                $sql .= ", senha = :senha";
                $param[":senha"] = $senhaHash;
            }

            $sql .= " WHERE id = :id";

            if ($this->banco->executar($sql, $param)) {
                http_response_code(200);
                $retorno->dados = ["mensagem" => "Usuario atualizado com sucesso"];
                return $retorno;
            }
            http_response_code(404);
            $retorno->erro = "Usuario nao encontrado";
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao atualizar usuario: " . $e->getMessage();
            return $retorno;
        }
    }

    public function deletar($id)
    {
        $retorno = new Retorno();

        // Validacao do ID
        if (empty($id) || ! is_numeric($id)) {
            http_response_code(400);
            $retorno->erro = "ID invalido";
            return $retorno;
        }

        try {
            if (parent::deletar($id)) {
                http_response_code(200);
                $retorno->dados = ["mensagem" => "Usuario deletado com sucesso"];
                return $retorno;
            }
            http_response_code(404);
            $retorno->erro = "Usuario nao encontrado";
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao deletar usuario: " . $e->getMessage();
            return $retorno;
        }
    }
}
