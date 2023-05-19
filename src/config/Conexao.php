<?php

namespace src\config;

use PDO;

require_once "config.php";
class Conexao extends PDO
{
    private static $instance = null;

    public function __construct()
    {
        try {
            parent::__construct("mysql:host=" . HOST . ";dbname=" . DBNAME, USER, PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (PDOException $e) {
            echo "Erro ao conectar com o banco de dados: " . $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Conexao();
        }
        return self::$instance;
    }
}