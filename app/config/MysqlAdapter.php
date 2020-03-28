<?php

//require '../../vendor/autoload.php';
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
    private $options;

    /**
     * Constructor de clase
     */
    function __construct()
    {

        $dotenv = Dotenv\Dotenv::create(__DIR__ . "../../../");
        $dotenv->load();

        $this->db = getenv('MYSQL_DATABASE');
        $this->hostname = getenv('MYSQL_HOSTNAME');
        $this->username = getenv('MYSQL_USER');
        $this->password = getenv('MYSQL_PASSWORD');
        $this->options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_SSL_KEY    => '../../mysql-files/client-key-prod.pem',
            PDO::MYSQL_ATTR_SSL_CERT => '../../mysql-files/client-cert-prod.pem',
            PDO::MYSQL_ATTR_SSL_CA    => '../../mysql-files/ca-prod.pem',
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true

        );

        /*$this->db = getenv('DB_DATABASE');
        $this->hostname = getenv('DB_HOSTNAME');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');*/
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
                $this->options
            );
            //echo "Connectado" . "\n";
            return $this->conn;
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage(), 0);
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
