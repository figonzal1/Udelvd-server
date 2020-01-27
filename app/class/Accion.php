<?php

/**
 * Objecto accion
 */

class Acciones
{

    private $id;
    private $nombre;


    function agregar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO accion (nombre) VALUES (?)"
            );

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
            echo "Fail insert: " . $e->getMessage() . "\n";
            return false;
        }
    }

    function actualizar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "UPDATE accion SET nombre=? WHERE id=?"
            );

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
            echo "Fail update: " . $e->getMessage() . "\n";
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
            echo "Fail search accion: " . $e->getMessage() . "\n";
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
            echo "Fail search lista acciones: " . $e->getMessage() . "\n";
            return false;
        }
    }

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
            echo "Fail delete accion: " . $e->getMessage() . "\n";
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
