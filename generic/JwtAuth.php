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
                http_response_code(406);
                echo json_encode([
                    'erro'     => 'token_nao_fornecido',
                    'mensagem' => 'Token não fornecido. Inclua o token no header Authorization (Bearer token).',
                ]);
                return false;
            }

            // Remover "Bearer " do token
            $autorizacao = $_SERVER['HTTP_AUTHORIZATION'];
            $token       = str_replace('Bearer ', '', $autorizacao);

            // Decodificar o token JWT
            $decodificar = JWT::decode($token, new Key($this->key, 'HS256'));

            // Verificar se o token expirou
            $hora = time();
            if ($hora > $decodificar->exp) {
                http_response_code(408);
                echo json_encode([
                    'erro'     => 'token_expirado',
                    'mensagem' => 'Seu token expirou. Faça login novamente para obter um novo token.',
                ]);
                return false;
            }

            // Token válido - retorna os dados decodificados
            return $decodificar;

        } catch (Exception $e) {
            // Qualquer erro na decodificação do token
            http_response_code(401);
            echo json_encode([
                'erro'     => 'token_invalido',
                'mensagem' => 'Token inválido ou malformado. Verifique se o token está correto.',
                'detalhes' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
