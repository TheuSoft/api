<?php
namespace service;

use dao\mysql\ProdutoDAO;
use generic\Retorno;

class ProdutoService extends ProdutoDAO
{
    public function listarProduto()
    {
        $retorno        = new Retorno();
        $retorno->dados = parent::listar();
        return $retorno;
    }

    public function inserir($nome, $descricao, $preco)
    {
        $retorno = new Retorno();

        // Validação dos campos obrigatórios
        if (empty($nome) || empty($descricao) || empty($preco)) {
            http_response_code(400);
            $retorno->erro = "Todos os campos sao obrigatorios: nome, descricao e preco";
            return $retorno;
        }

        // Validação do preço
        if (! is_numeric($preco) || $preco <= 0) {
            http_response_code(400);
            $retorno->erro = "O preco deve ser um valor numerico maior que zero";
            return $retorno;
        }

        try {
            if (parent::inserir($nome, $descricao, $preco)) {
                http_response_code(201);
                $retorno->dados = ["mensagem" => "Produto cadastrado com sucesso"];
                return $retorno;
            }
            http_response_code(500);
            $retorno->erro = "Erro ao cadastrar produto";
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao cadastrar produto: " . $e->getMessage();
            return $retorno;
        }
    }

    public function alterar($id, $nome, $descricao, $preco)
    {
        $retorno = new Retorno();

        // Validação do ID
        if (empty($id) || ! is_numeric($id)) {
            http_response_code(400);
            $retorno->erro = "ID invalido";
            return $retorno;
        }

        // Validação dos campos obrigatórios
        if (empty($nome) || empty($descricao) || empty($preco)) {
            http_response_code(400);
            $retorno->erro = "Todos os campos sao obrigatorios: nome, descricao e preco";
            return $retorno;
        }

        // Validação do preço
        if (! is_numeric($preco) || $preco <= 0) {
            http_response_code(400);
            $retorno->erro = "O preco deve ser um valor numerico maior que zero";
            return $retorno;
        }

        try {
            if (parent::alterar($id, $nome, $descricao, $preco)) {
                http_response_code(200);
                $retorno->dados = ["mensagem" => "Produto atualizado com sucesso"];
                return $retorno;
            }
            http_response_code(404);
            $retorno->erro = "Produto nao encontrado";
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao atualizar produto: " . $e->getMessage();
            return $retorno;
        }
    }

    public function listarId($id)
    {
        $retorno = new Retorno();

        // Validação do ID
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
            $retorno->erro = "Produto nao encontrado";
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao buscar produto: " . $e->getMessage();
            return $retorno;
        }
    }

    public function deletar($id)
    {
        $retorno = new Retorno();

        // Validação do ID
        if (empty($id) || ! is_numeric($id)) {
            http_response_code(400);
            $retorno->erro = "ID invalido";
            return $retorno;
        }

        try {
            if (parent::deletar($id)) {
                http_response_code(200);
                $retorno->dados = ["mensagem" => "Produto deletado com sucesso"];
                return $retorno;
            }
            http_response_code(404);
            $retorno->erro = "Produto nao encontrado";
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao deletar produto: " . $e->getMessage();
            return $retorno;
        }
    }
}
