<?php

use Dotenv\Dotenv;

date_default_timezone_set('America/Santiago');

/**
 * Adaptador mysql para conexion a BD
 */
class MysqlAdapter
{
    private ?object $conn;
    private string $db;
    private string $hostname;
    private string $username;
    private string $password;

    /**
     * Constructor de clase
     */
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . "../../../");
        $dotenv->load();

        $this->hostname = $_ENV['MYSQL_HOSTNAME'];
        $this->db = $_ENV['MYSQL_DATABASE'];
        $this->username = $_ENV['MYSQL_USER'];
        $this->password = $_ENV['MYSQL_ROOT_PASSWORD'];

        /*$this->options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            //? COMENTAR PARA LOCALHOST
            PDO::MYSQL_ATTR_SSL_KEY => '../../mysql-files/client-key-usm.pem',
            PDO::MYSQL_ATTR_SSL_CERT => '../../mysql-files/client-cert-usm.pem',
            PDO::MYSQL_ATTR_SSL_CA => '../../mysql-files/ca-usm.pem',
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false

        );*/
    }

    /**
     * Conexion da base de datos
     * @noinspection ForgottenDebugOutputInspection
     */
    public function connect(): ?object
    {

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->hostname . ";dbname=" . $this->db,
                $this->username,
                $this->password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
            return $this->conn;
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage(), 0);
            return null;
        }
    }

    /**
     * Desconectar bd;
     */
    public function disconnect(): ?object
    {
        $this->conn = null;
        return $this->conn;
    }
}
