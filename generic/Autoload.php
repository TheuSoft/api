<?php
// Carregar o autoload do Composer para bibliotecas externas (Firebase JWT)
require_once __DIR__ . '/../vendor/autoload.php';

// Autoloader manual para as classes do projeto
spl_autoload_register(function ($class) {
    // Substituir namespace separators por directory separators
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Caminho completo do arquivo
    $file = __DIR__ . '/../' . $class . '.php';

    // Verificar se o arquivo existe antes de incluir
    if (file_exists($file)) {
        include $file;
    }
});
