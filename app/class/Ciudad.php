<?php
/** @noinspection ForgottenDebugOutputInspection */

/**
 * Objeto ciudad
 */
class Ciudad
{

    private string $id;
    private string $nombre;

    public function agregar($conn)
    {
        try {

            $stmt = $conn->prepare(
                "INSERT INTO ciudad (nombre) VALUES (?)"
            );

            $stmt->execute(
                array(
                    $this->nombre
                )
            );

            //Consultar ultimo id
            $stmt = $conn->query("SELECT MAX(id) as id from ciudad");
            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lastId['id'];
        } catch (PDOException $e) {
            error_log("Fail insert: " . $e->getMessage());
            return false;
        }
    }

    public function buscarCiudadPorNombre($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * from ciudad WHERE nombre=?"
            );
            $stmt->execute(array($this->nombre));

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search ciudad: " . $e->getMessage());
            return false;
        }
    }

    public function buscarTodos($conn)
    {
        try {
            return $conn->query("SELECT * from ciudad")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista ciudades: " . $e->getMessage());
            return false;
        }
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }
}
