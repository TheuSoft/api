<?php
namespace controller;

use service\ProdutoService;

class Produto
{
    public function listar()
    {
        $produto = new ProdutoService();
        return $produto->listarProduto();
    }

    public function cadastrar($nome, $descricao, $preco)
    {
        $produto = new ProdutoService();
        return $produto->inserir($nome, $descricao, $preco);
    }

    public function alterar($id, $nome, $descricao, $preco)
    {
        $produto = new ProdutoService();
        return $produto->alterar($id, $nome, $descricao, $preco);
    }

    public function listarId($id)
    {
        $produto = new ProdutoService();
        return $produto->listarId($id);
    }

    public function deletar($id)
    {
        $produto = new ProdutoService();
        return $produto->deletar($id);
    }
}
