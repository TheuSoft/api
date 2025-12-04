<?php
namespace generic;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth
{
    private string $key = "adlsfh ljsikdfjalsikdfalj3948fjejh888'####jnaskfj3947*($$$@_)hdfbajhfawekjh@$$'$(@jsfglkcsfjgco1sfgghjc1o1";

    // Criar token JWT
    public function criarChave($dados)
    {
        $hora    = time();
        $payload = [
            'iat' => $hora,
            'exp' => $hora + 100000,
            'uid' => $dados,
        ];

        $jwt = JWT::encode($payload, $this->key, 'HS256');
        return $jwt;
    }

    // Verificar token JWT
    public function verificar()
    {
        try {
            // Verificar se o header Authorization existe
            if (! isset($_SERVER['HTTP_AUTHORIZATION'])) {
                http_response_code(401);
                $retorno       = new Retorno();
                $retorno->erro = "Token nao fornecido. Inclua o token no header Authorization (Bearer token).";
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode($retorno);
                exit;
            }

            // Remover "Bearer " do token
            $autorizacao = $_SERVER['HTTP_AUTHORIZATION'];
            $token       = str_replace('Bearer ', '', $autorizacao);

            // Decodificar o token JWT
            $decodificar = JWT::decode($token, new Key($this->key, 'HS256'));

            // Verificar se o token expirou
            $hora = time();
            if ($hora > $decodificar->exp) {
                http_response_code(401);
                $retorno       = new Retorno();
                $retorno->erro = "Seu token expirou. Faca login novamente para obter um novo token.";
                header("Content-Type: application/json; charset=utf-8");
                echo json_encode($retorno);
                exit;
            }

            // Token válido - retorna os dados decodificados
            return $decodificar;

        } catch (Exception $e) {
            // Qualquer erro na decodificação do token
            http_response_code(401);
            $retorno        = new Retorno();
            $retorno->erro  = "Token invalido ou malformado. Verifique se o token esta correto.";
            $retorno->dados = ["detalhes" => $e->getMessage()];
            header("Content-Type: application/json; charset=utf-8");
            echo json_encode($retorno);
            exit;
        }
    }
}
