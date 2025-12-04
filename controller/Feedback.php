<?php
namespace controller;

use service\FeedbackService;

class Feedback
{
    private $service;

    public function __construct()
    {
        $this->service = new FeedbackService();
    }

    public function listar()
    {
        return $this->service->listar();
    }

    public function buscar($id = null)
    {
        if (! $id) {
            return ["erro" => "ID não fornecido"];
        }

        return $this->service->listarId($id);
    }

    public function inserir($produto_id, $usuario_id, $comentario, $nota)
    {
        if (empty($produto_id) || empty($usuario_id) || empty($comentario) || $nota === null) {
            return ["erro" => "Campos obrigatórios: produto_id, usuario_id, comentario, nota"];
        }
        return ["mensagem" => $this->service->inserir($produto_id, $usuario_id, $comentario, $nota)];
    }

    public function deletar($id)
    {
        if (empty($id)) {
            return ["erro" => "ID é obrigatório"];
        }

        return ["mensagem" => $this->service->deletar($id)];
    }

    public function listarPorProduto($produto_id = null)
    {
        if (! $produto_id) {
            return ["erro" => "produto_id não fornecido"];
        }

        return $this->service->listarPorProduto($produto_id);
    }

    public function listarPorUsuario($usuario_id = null)
    {
        if (! $usuario_id) {
            return ["erro" => "usuario_id não fornecido"];
        }

        return $this->service->listarPorUsuario($usuario_id);
    }
}
