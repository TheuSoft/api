<?php
namespace controller;

use service\ProdutoService;

class Produto
{
    private $service;

    public function __construct()
    {
        $this->service = new ProdutoService();
    }

    public function listar()
    {
        return $this->service->listarProduto();
    }

    public function buscar($id = null)
    {
        if (! $id) {
            return ["erro" => "ID não fornecido"];
        }

        return $this->service->listarId($id);
    }

    public function inserir($nome, $descricao, $preco)
    {
        if (empty($nome) || empty($descricao) || $preco === null) {
            return ["erro" => "Campos obrigatórios: nome, descricao, preco"];
        }
        $ok = $this->service->inserir($nome, $descricao, $preco);
        if ($ok) {
            return ["mensagem" => "Dados Salvo com Sucesso!"];
        }

        return ["erro" => "Falha ao inserir"];
    }

    public function alterar($id, $nome, $descricao, $preco)
    {
        if (empty($id) || empty($nome) || empty($descricao) || $preco === null) {
            return ["erro" => "Campos obrigatórios: id, nome, descricao, preco"];
        }
        $ok = $this->service->alterar($id, $nome, $descricao, $preco);
        if ($ok) {
            return ["mensagem" => "Alterado com sucesso"];
        }

        return ["erro" => "Registro não alterado"];
    }

    public function deletar($id)
    {
        if (empty($id)) {
            return ["erro" => "ID é obrigatório"];
        }

        $ok = $this->service->deletar($id);
        if ($ok) {
            return ["mensagem" => "Excluído com sucesso"];
        }

        return ["erro" => "Registro não encontrado ou não excluído"];
    }
}
