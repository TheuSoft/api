<?php
// filepath: c:\xampp\htdocs\api\generic\Controller.php
namespace generic;

class Controller
{
    private $rotas = null;

    public function __construct()
    {
        $this->rotas = new Rotas();
    }

    public function verificarChamadas($rota)
    {
        $retorno = $this->rotas->executar($rota);
        if ($retorno) {
            header("Content-Type: application/json; charset=utf-8");
            $json = json_encode(
                $retorno,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
            echo $json;
        }
    }
}
