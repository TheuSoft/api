<?php
namespace generic;

use PDO;

class MysqlSingleton
{
    private static $instance = null;
    private $conexao         = null;
    private $usuario         = 'root';
    private $senha           = '';

    private function __construct()
    {
        if ($this->conexao == null) {
            $this->conexao = new PDO('mysql:host=localhost;dbname=feedback_produtos', $this->usuario, $this->senha);
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new MysqlSingleton();
        }
        return self::$instance;
    }

    public function executar($query, $param = [])
    {
        if ($this->conexao) {
            $sth = $this->conexao->prepare($query);
            foreach ($param as $k => $v) {
                $sth->bindValue($k, $v);
            }
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
