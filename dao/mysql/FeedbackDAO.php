<?php
namespace dao\mysql;

use dao\IFeedbackDAO;
use generic\MysqlFactory;

class FeedbackDAO extends MysqlFactory implements IFeedbackDAO
{

    public function listar()
    {
        $sql = "select f.id, f.usuario_id, f.produto_id, f.nota, f.comentario,
                       u.nome as usuario_nome, p.nome as produto_nome
                from feedback f
                inner join usuarios u on f.usuario_id = u.id
                inner join produtos p on f.produto_id = p.id";
        $retorno = $this->banco->executar($sql);
        return $retorno;
    }
    public function listarId($id)
    {
        $sql = "select f.id, f.usuario_id, f.produto_id, f.nota, f.comentario,
                       u.nome as usuario_nome, p.nome as produto_nome
                from feedback f
                inner join usuarios u on f.usuario_id = u.id
                inner join produtos p on f.produto_id = p.id
                where f.id=:id";
        $param = [
            ":id" => $id,
        ];
        $retorno = $this->banco->executar($sql, $param);
        return $retorno;
    }

    public function inserir($usuario_id, $produto_id, $nota, $comentario)
    {
        $sql   = "insert into feedback (usuario_id,produto_id,nota,comentario) values (:usuario_id,:produto_id,:nota,:comentario)";
        $param = [
            ":usuario_id" => $usuario_id,
            ":produto_id" => $produto_id,
            ":nota"       => $nota,
            ":comentario" => $comentario,
        ];
        $retorno = $this->banco->executar($sql, $param);
        return true;
    }

    public function listarPorProduto($produto_id)
    {
        $sql = "select f.id, f.usuario_id, f.produto_id, f.nota, f.comentario,
                       u.nome as usuario_nome, p.nome as produto_nome
                from feedback f
                inner join usuarios u on f.usuario_id = u.id
                inner join produtos p on f.produto_id = p.id
                where f.produto_id=:produto_id";
        $param = [
            ":produto_id" => $produto_id,
        ];
        $retorno = $this->banco->executar($sql, $param);
        return $retorno;
    }

    public function listarPorUsuario($usuario_id)
    {
        $sql = "select f.id, f.usuario_id, f.produto_id, f.nota, f.comentario,
                       u.nome as usuario_nome, p.nome as produto_nome
                from feedback f
                inner join usuarios u on f.usuario_id = u.id
                inner join produtos p on f.produto_id = p.id
                where f.usuario_id=:usuario_id";
        $param = [
            ":usuario_id" => $usuario_id,
        ];
        $retorno = $this->banco->executar($sql, $param);
        return $retorno;
    }

    public function alterar($id, $usuario_id, $produto_id, $nota, $comentario)
    {
        $sql = "UPDATE feedback
            SET produto_id = :produto_id,
                nota = :nota,
                comentario = :comentario
            WHERE id = :id
            AND usuario_id = :usuario_id";

        $param = [
            ":id"         => $id,
            ":usuario_id" => $usuario_id,
            ":produto_id" => $produto_id,
            ":nota"       => $nota,
            ":comentario" => $comentario,
        ];

        $this->banco->executar($sql, $param);
        return true;
    }

    public function deletar($id)
    {
        $sql   = "delete from feedback where id=:id";
        $param = [
            ":id" => $id,
        ];
        $retorno = $this->banco->executar($sql, $param);
        return true;
    }
}
