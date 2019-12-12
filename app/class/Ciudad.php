<?php

/**
 * Objeto ciudad
 */

class Ciudad
{

    private $id;
    private $nombre;

    function agregar($conn)
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

    function buscarCiudadPorNombre($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * from ciudad WHERE nombre=?"
            );
            $stmt->execute(array($this->nombre));

            $ciudad = $stmt->fetch(PDO::FETCH_ASSOC);
            return $ciudad;
        } catch (PDOException $e) {
            error_log("Fail search ciudad: " . $e->getMessage(), 0);
            return false;
        }
    }

    function buscarTodos($conn)
    {
        try {
            $stmt = $conn->query(
                "SELECT * from ciudad"
            );

            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $listado;
        } catch (PDOException $e) {
            error_log("Fail search lista ciudades: " . $e->getMessage(), 0);
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
