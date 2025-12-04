<?php
namespace generic;

class Rotas
{
    private $endpoints = [];

    public function __construct()
    {
        // rotas para o acesso as chamadas
        $this->endpoints = [
            "auth/login"       => new Acao([
                Acao::POST => new Endpoint("Auth", "login", false),
            ]),
            "produto"          => new Acao([
                Acao::POST   => new Endpoint("Produto", "inserir", true),
                Acao::GET    => new Endpoint("Produto", "listar", true),
                Acao::PUT    => new Endpoint("Produto", "alterar", true),
                Acao::DELETE => new Endpoint("Produto", "deletar", true),
            ]),
            "produto/buscar"   => new Acao([
                Acao::GET => new Endpoint("Produto", "buscar", true),
            ]),
            "usuario"          => new Acao([
                Acao::GET    => new Endpoint("Usuario", "listar", true),
                Acao::POST   => new Endpoint("Usuario", "cadastrar", false),
                Acao::PUT    => new Endpoint("Usuario", "alterar", true),
                Acao::DELETE => new Endpoint("Usuario", "deletar", true),
            ]),
            "usuario/buscar"   => new Acao([
                Acao::GET => new Endpoint("Usuario", "buscar", true),
            ]),
            "feedback"         => new Acao([
                Acao::POST   => new Endpoint("Feedback", "inserir", true),
                Acao::GET    => new Endpoint("Feedback", "listar", true),
                Acao::DELETE => new Endpoint("Feedback", "deletar", true),
            ]),
            "feedback/buscar"  => new Acao([
                Acao::GET => new Endpoint("Feedback", "buscar", true),
            ]),
            "feedback/produto" => new Acao([
                Acao::GET => new Endpoint("Feedback", "listarPorProduto", true),
            ]),
            "feedback/usuario" => new Acao([
                Acao::GET => new Endpoint("Feedback", "listarPorUsuario", true),
            ]),
        ];
    }

    public function executar($rota)
    {
        if (isset($this->endpoints[$rota])) {
            $endpoint       = $this->endpoints[$rota];
            $dados          = $endpoint->executar();
            $retorno        = new Retorno();
            $retorno->dados = $dados;
            return $retorno;
        }
        return null;
    }
}
