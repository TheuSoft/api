<?php
namespace service;

use dao\mysql\FeedbackDAO;
use generic\Retorno;

class FeedbackService extends FeedbackDAO
{
    public function listarFeedback()
    {
        $retorno        = new Retorno();
        $retorno->dados = parent::listar();
        return $retorno;
    }

    public function inserir($usuario_id, $produto_id, $nota, $comentario)
    {
        $retorno = new Retorno();

        // Validação dos campos obrigatórios
        if (empty($usuario_id) || empty($produto_id) || empty($nota)) {
            http_response_code(400);
            $retorno->erro = "Os campos usuario_id, produto_id e nota sao obrigatorios";
            return $retorno;
        }

        // Validação dos IDs
        if (! is_numeric($usuario_id) || ! is_numeric($produto_id)) {
            http_response_code(400);
            $retorno->erro = "usuario_id e produto_id devem ser valores numericos";
            return $retorno;
        }

        // Validação da nota (assumindo escala de 1 a 5)
        if (! is_numeric($nota) || $nota < 1 || $nota > 5) {
            http_response_code(400);
            $retorno->erro = "A nota deve ser um valor entre 1 e 5";
            return $retorno;
        }

        try {
            if (parent::inserir($usuario_id, $produto_id, $nota, $comentario)) {
                http_response_code(201);
                $retorno->dados = ["mensagem" => "Feedback cadastrado com sucesso"];
                return $retorno;
            }
            http_response_code(500);
            $retorno->erro = "Erro ao cadastrar feedback";
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao cadastrar feedback: " . $e->getMessage();
            return $retorno;
        }
    }

    public function alterar($id, $usuario_id, $produto_id, $nota, $comentario)
    {
        $retorno = new Retorno();

        // Validação do ID
        if (empty($id) || ! is_numeric($id)) {
            http_response_code(400);
            $retorno->erro = "ID invalido";
            return $retorno;
        }

        // Validação dos campos obrigatórios
        if (empty($usuario_id) || empty($produto_id) || empty($nota)) {
            http_response_code(400);
            $retorno->erro = "Os campos usuario_id, produto_id e nota sao obrigatorios";
            return $retorno;
        }

        // Validação dos IDs
        if (! is_numeric($usuario_id) || ! is_numeric($produto_id)) {
            http_response_code(400);
            $retorno->erro = "usuario_id e produto_id devem ser valores numericos";
            return $retorno;
        }

        // Validação da nota
        if (! is_numeric($nota) || $nota < 1 || $nota > 5) {
            http_response_code(400);
            $retorno->erro = "A nota deve ser um valor entre 1 e 5";
            return $retorno;
        }

        try {
            if (parent::alterar($id, $usuario_id, $produto_id, $nota, $comentario)) {
                http_response_code(200);
                $retorno->dados = ["mensagem" => "Feedback atualizado com sucesso"];
                return $retorno;
            }
            http_response_code(404);
            $retorno->erro = "Feedback nao encontrado";
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao atualizar feedback: " . $e->getMessage();
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
            $retorno->erro = "Feedback nao encontrado";
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao buscar feedback: " . $e->getMessage();
            return $retorno;
        }
    }

    public function listarPorProduto($produto_id)
    {
        $retorno = new Retorno();

        // Validação do ID do produto
        if (empty($produto_id) || ! is_numeric($produto_id)) {
            http_response_code(400);
            $retorno->erro = "produto_id invalido";
            return $retorno;
        }

        try {
            $retorno->dados = parent::listarPorProduto($produto_id);
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao buscar feedbacks: " . $e->getMessage();
            return $retorno;
        }
    }

    public function listarPorUsuario($usuario_id)
    {
        $retorno = new Retorno();

        // Validação do ID do usuário
        if (empty($usuario_id) || ! is_numeric($usuario_id)) {
            http_response_code(400);
            $retorno->erro = "usuario_id invalido";
            return $retorno;
        }

        try {
            $retorno->dados = parent::listarPorUsuario($usuario_id);
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao buscar feedbacks: " . $e->getMessage();
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
                $retorno->dados = ["mensagem" => "Feedback deletado com sucesso"];
                return $retorno;
            }
            http_response_code(404);
            $retorno->erro = "Feedback nao encontrado";
            return $retorno;
        } catch (\Exception $e) {
            http_response_code(500);
            $retorno->erro = "Erro ao deletar feedback: " . $e->getMessage();
            return $retorno;
        }
    }
}
