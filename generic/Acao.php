<?php
namespace generic;

use ReflectionMethod;

class Acao
{
    const POST   = "POST";
    const GET    = "GET";
    const PUT    = "PUT";
    const PATCH  = "PATCH";
    const DELETE = "DELETE";

    private $endpoint;

    public function __construct($endpoint = [])
    {
        $this->endpoint = $endpoint;
    }

    public function executar()
    {
        $end = $this->endpointMetodo();

        if ($end) {
            if ($end->autenticar) {
                $jwt    = new JwtAuth();
                $decode = $jwt->verificar();
                if (! $decode) {
                    return;
                }
            }

            $reflectMetodo = new ReflectionMethod($end->classe, $end->execucao);
            $parametros    = $reflectMetodo->getParameters();
            $returnParam   = $this->getParam();

            if ($parametros) {
                $para = [];
                foreach ($parametros as $v) {
                    $name = $v->getName();

                    if (array_key_exists($name, $returnParam)) {
                        $para[$name] = $returnParam[$name];
                        continue;
                    }

                    if ($v->isDefaultValueAvailable()) {
                        $para[$name] = $v->getDefaultValue();
                        continue;
                    }

                    http_response_code(400);
                    $retorno       = new Retorno();
                    $retorno->erro = "Parametro obrigatorio ausente: {$name}";
                    header("Content-Type: application/json; charset=utf-8");
                    echo json_encode($retorno);
                    exit;
                }
                return $reflectMetodo->invokeArgs(new $end->classe(), $para);
            }

            return $reflectMetodo->invoke(new $end->classe());
        }

        http_response_code(404);
        $retorno       = new Retorno();
        $retorno->erro = "Rota nao encontrada";
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($retorno);
        exit;
    }

    private function endpointMetodo()
    {
        return isset($this->endpoint[$_SERVER["REQUEST_METHOD"]])
            ? $this->endpoint[$_SERVER["REQUEST_METHOD"]]
            : null;
    }

    private function getPost()
    {
        return $_POST ?? [];
    }

    private function getGet()
    {
        if ($_GET) {
            $get = $_GET;
            unset($get["param"]);
            return $get;
        }
        return [];
    }

    private function getInput()
    {
        $input = file_get_contents("php://input");
        if (! $input) {
            return [];
        }

        $decoded = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            return [];
        }

        return $decoded;
    }

    public function getParam()
    {
        $post  = $this->getPost();
        $get   = $this->getGet();
        $input = $this->getInput();

        return array_merge(
            is_array($post) ? $post : [],
            is_array($get) ? $get : [],
            is_array($input) ? $input : []
        );
    }
}
