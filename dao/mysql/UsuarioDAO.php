<?php
namespace dao\mysql;

use dao\IUsuarioDAO;
use generic\MysqlFactory;

class UsuarioDAO extends MysqlFactory implements IUsuarioDAO
{
    public function listar()
    {
        $sql     = "SELECT id, nome, email FROM usuarios";
        $retorno = $this->banco->executar($sql);
        return $retorno;
    }

    public function listarId($id)
    {
        $sql     = "SELECT id, nome, email FROM usuarios WHERE id = :id";
        $param   = [":id" => $id];
        $retorno = $this->banco->executar($sql, $param);
        return $retorno;
    }

    /**
     * Buscar usuário por email
     */
    public function buscarPorEmail($email)
    {
        $sql     = "SELECT id, nome, email, senha FROM usuarios WHERE email = :email";
        $param   = [":email" => $email];
        $retorno = $this->banco->executar($sql, $param);
        return $retorno ? $retorno[0] : null;
    }

    /**
     * Verificar login do usuário
     */
    public function verificaLogin($email, $senha)
    {
        try {
            $sql     = "SELECT id, nome, email, senha FROM usuarios WHERE email = :email";
            $param   = [":email" => $email];
            $retorno = $this->banco->executar($sql, $param);

            if ($retorno && count($retorno) > 0) {
                // Verificar senha com password_verify
                if (password_verify($senha, $retorno[0]['senha'])) {
                    return $retorno;
                }
            }

            return null;

        } catch (\PDOException $e) {
            // Lança exception para o Service tratar
            throw new \Exception("Erro ao acessar o banco de dados: " . $e->getMessage());
        }
    }

    public function inserir($nome, $email)
    {
        // Gerar senha padrão hash
        $senhaHash = password_hash('123456', PASSWORD_DEFAULT);

        $sql   = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $param = [
            ":nome"  => $nome,
            ":email" => $email,
            ":senha" => $senhaHash,
        ];
        $retorno = $this->banco->executar($sql, $param);
        return true;
    }

    public function alterar($id, $nome, $email)
    {
        $sql   = "UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id";
        $param = [
            ":nome"  => $nome,
            ":email" => $email,
            ":id"    => $id,
        ];
        $retorno = $this->banco->executar($sql, $param);
        return true;
    }

    public function deletar($id)
    {
        $sql     = "DELETE FROM usuarios WHERE id = :id";
        $param   = [":id" => $id];
        $retorno = $this->banco->executar($sql, $param);
        return true;
    }
}
