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
                    return ["erro" => "Parâmetro obrigatório ausente: {$name}"];
                }
                return $reflectMetodo->invokeArgs(new $end->classe(), $para);
            }

            return $reflectMetodo->invoke(new $end->classe());
        }

        http_response_code(404);
        return ["erro" => "Rota não encontrada"];
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
        return $input ? json_decode($input, true) : [];
    }

    public function getParam()
    {
        return array_merge($this->getPost(), $this->getGet(), $this->getInput());
    }
}
