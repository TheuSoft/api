<?php
namespace controller;

use service\FeedbackService;

class Feedback
{
    public function listar()
    {
        $feedback = new FeedbackService();
        return $feedback->listarFeedback();
    }

    public function cadastrar($usuario_id, $produto_id, $nota, $comentario = null)
    {
        $feedback = new FeedbackService();
        return $feedback->inserir($usuario_id, $produto_id, $nota, $comentario);
    }

    public function alterar($id, $usuario_id, $produto_id, $nota, $comentario = null)
    {
        $feedback = new FeedbackService();
        return $feedback->alterar($id, $usuario_id, $produto_id, $nota, $comentario);
    }

    public function listarId($id)
    {
        $feedback = new FeedbackService();
        return $feedback->listarId($id);
    }

    public function listarPorProduto($produto_id)
    {
        $feedback = new FeedbackService();
        return $feedback->listarPorProduto($produto_id);
    }

    public function listarPorUsuario($usuario_id)
    {
        $feedback = new FeedbackService();
        return $feedback->listarPorUsuario($usuario_id);
    }

    public function deletar($id)
    {
        $feedback = new FeedbackService();
        return $feedback->deletar($id);
    }
}
