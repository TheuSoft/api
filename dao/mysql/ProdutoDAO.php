<?php
namespace dao\mysql;

use dao\IProdutoDAO;
use generic\MysqlFactory;

class ProdutoDAO extends MysqlFactory implements IProdutoDAO
{

    public function listar()
    {
        $sql     = "select id,nome,descricao,preco from produtos";
        $retorno = $this->banco->executar($sql);
        return $retorno;
    }
    public function listarId($id)
    {
        $sql   = "select id,nome,descricao,preco from produtos where id=:id";
        $param = [
            ":id" => $id,
        ];
        $retorno = $this->banco->executar($sql, $param);
        return $retorno;
    }

    public function inserir($nome, $descricao, $preco)
    {
        $sql   = "insert into produtos (nome,descricao,preco) values (:nome,:descricao,:preco)";
        $param = [
            ":nome"      => $nome,
            ":descricao" => $descricao,
            ":preco"     => $preco,
        ];
        $retorno = $this->banco->executar($sql, $param);
        return true;
    }
    public function alterar($id, $nome, $descricao, $preco)
    {
        $sql   = "update produtos set nome=:nome,descricao=:descricao,preco=:preco where id=:id";
        $param = [
            ":nome"      => $nome,
            ":descricao" => $descricao,
            ":preco"     => $preco,
            ":id"        => $id,
        ];
        $retorno = $this->banco->executar($sql, $param);
        return true;
    }

    public function deletar($id)
    {
        $sql   = "delete from produtos where id=:id";
        $param = [
            ":id" => $id,
        ];
        $retorno = $this->banco->executar($sql, $param);
        return true;
    }
}
