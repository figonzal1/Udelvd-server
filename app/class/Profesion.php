<?php /** @noinspection ForgottenDebugOutputInspection */


/**
 * CLASE PROFESION
 */
class Profesion
{

    private string $nombre;


    public function agregar($conn)
    {
        try {

            $stmt = $conn->prepare(
                "INSERT INTO profesion (nombre) VALUES (?)"
            );

            $stmt->execute(array($this->nombre));

            //Consultar ultimo id
            $stmt = $conn->query("SELECT MAX(id) as id from profesion");
            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lastId['id'];
        } catch (PDOException $e) {
            error_log("Fail insert profesion: " . $e->getMessage(), 0);
            return false;
        }
    }

    public function buscarProfesionPorNombre($conn)
    {

        try {

            $stmt = $conn->prepare(
                "SELECT * FROM profesion WHERE nombre=?"
            );

            $stmt->execute(array($this->nombre));

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search profesion: " . $e->getMessage());
            return false;
        }
    }

    public function buscarTodos($conn)
    {
        try {

            return $conn->query("SELECT * FROM profesion")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista profesiones: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
}
