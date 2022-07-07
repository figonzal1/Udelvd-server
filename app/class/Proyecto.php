<?php /** @noinspection ForgottenDebugOutputInspection */

class Proyecto
{
    private string $id;
    private string $nombre;

    public function agregar($conn)
    {

        $sql = "INSERT INTO 
            proyecto (nombre) 
            VALUES (?)";

        try {
            $stmt = $conn->prepare($sql);

            $stmt->execute(array($this->nombre));

            $stmt = $conn->query("SELECT MAX(id) as id from proyecto");
            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);

            return $lastId['id'];
        } catch (PDOException $e) {
            error_log("Fail insert proyecto: " . $e->getMessage());
            return false;
        }
    }

    public function buscarProyecto($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * FROM proyecto WHERE id=?"
            );
            $stmt->execute(array($this->id));

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search proyecto: " . $e->getMessage());
            return false;
        }
    }

    public function buscarTodos($conn)
    {
        try {

            return $conn->query("SELECT * FROM proyecto")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista proyectos: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
}