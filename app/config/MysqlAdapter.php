<?php

/**
 * Adaptador mysql para conexion a BD
 */
class MysqlAdapter
{
    private $conn;
    private $db;
    private $hostname;
    private $username;
    private $password;

    /**
     * Constructor de clase
     */
    function __construct()
    {
        $dotenv = Dotenv\Dotenv::create(__DIR__."../../../");
        $dotenv->load();

        $this->db = getenv('DB_DATABASE');
        $this->hostname = getenv('DB_HOSTNAME');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');
    }

    /**
     * Conexion da base de datos
     */
    function connect()
    {

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->hostname . ";dbname=" . $this->db . "",
                $this->username,
                $this->password,
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
            //echo "Connectado" . "\n";
            return $this->conn;
        } catch (PDOException $e) {
            //echo "Connection failed: " . $e->getMessage() . "\n";
            error_log("Connection failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Desconectar bd;
     */
    function disconnect()
    {
        $this->conn = null;
    }
}
