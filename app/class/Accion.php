<?php

/**
 * Objecto accion
 */

class Acciones
{

    private $id;
    private $nombre;


    function agregar($conn, $idioma)
    {
        $sql = "INSERT INTO accion_" . $idioma . " (nombre) VALUES (?)";
        try {
            $stmt = $conn->prepare($sql);

            $stmt->execute(
                array(
                    $this->nombre
                )
            );

            //Consultar ultimo id
            $stmt = $conn->query("SELECT MAX(id) as id from accion");
            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lastId['id'];
        } catch (PDOException $e) {
            error_log("Fail insert: " . $e->getMessage(), 0);
            return false;
        }
    }

    function actualizar($conn, $idioma)
    {
        $sql = "UPDATE accion_" . $idioma . " SET nombre=? WHERE id=?";

        try {
            $stmt = $conn->prepare($sql);

            $stmt->execute(array(
                $this->nombre,
                $this->id
            ));

            if ($stmt->rowCount() == 0) {
                return "iguales";
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail update: " . $e->getMessage(), 0);
            return false;
        }
    }

    function buscarAccion($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * FROM accion WHERE id=?"
            );
            $stmt->execute(array($this->id));

            $accion = $stmt->fetch(PDO::FETCH_ASSOC);
            return $accion;
        } catch (PDOException $e) {
            error_log("Fail search accion: " . $e->getMessage(), 0);
            return false;
        }
    }

    function buscarTodos($conn)
    {
        try {

            $stmt = $conn->query(
                "SELECT * FROM accion"
            );
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $listado;
        } catch (PDOException $e) {
            error_log("Fail search lista acciones: " . $e->getMessage(), 0);
            return false;
        }
    }

    //TODO: Por checkear aqui e implementar en android
    function eliminar($conn)
    {

        try {
            $stmt = $conn->prepare(
                "DELETE FROM accion WHERE id=?"
            );

            $stmt->execute(array($this->id));
            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail delete accion: " . $e->getMessage(), 0);
            return false;
        }
    }

    /**
     * GETTERS & SETTERS
     */
    function getNombre()
    {
        return $this->nombre;
    }
    function getId()
    {
        return $this->id;
    }
    function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    function setId($id)
    {
        $this->id = $id;
    }
}
