<?php
// Carregar o autoloader (que já inclui o vendor/autoload.php)
include "generic/Autoload.php";

use generic\Controller;

// Entrada de dados
// Verifica se o parâmetro do endpoint existe
if (isset($_GET["param"])) {
    $controller = new Controller();
    // Chamada para verificar se o endpoint está público
    $controller->verificarChamadas($_GET["param"]);
}
