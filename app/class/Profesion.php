<?php


/**
 * CLASE PROFESION
 */

class Profesion
{


    private $id;
    private $nombre;


    function agregar($conn)
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
            error_log("Fail insert: " . $e->getMessage(), 0);
            return false;
        }
    }

    function buscarProfesionPorNombre($conn)
    {

        try {

            $stmt = $conn->prepare(
                "SELECT * FROM profesion WHERE nombre=?"
            );

            $stmt->execute(array($this->nombre));

            $profesion = $stmt->fetch(PDO::FETCH_ASSOC);

            return $profesion;
        } catch (PDOException $e) {
            error_log("Fail search profesion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * GETTERS & SETTERS
     */
    function getId()
    {
        return $this->id;
    }
    function getNombre()
    {
        return $this->nombre;
    }
    function setId($id)
    {
        $this->id = $id;
    }
    function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
}
