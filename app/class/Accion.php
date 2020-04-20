<?php

/**
 * Objecto accion
 */

class Acciones
{

    private $id;
    private $nombre_es;
    private $nombre_en;


    function agregar($conn)
    {
        $sql = "INSERT INTO accion (nombre_es,nombre_en) VALUES (?,?)";
        try {
            $stmt = $conn->prepare($sql);

            $stmt->execute(
                array(
                    $this->nombre_es,
                    $this->nombre_en
                )
            );

            //Consultar ultimo id
            $stmt = $conn->query("SELECT MAX(id) as id from accion");
            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lastId['id'];
        } catch (PDOException $e) {
            error_log("Fail insert accion: " . $e->getMessage(), 0);
            return false;
        }
    }

    function actualizar($conn)
    {
        $sql = "UPDATE accion SET nombre_es=?,nombre_en=? WHERE id=?";

        try {
            $stmt = $conn->prepare($sql);

            $stmt->execute(array(
                $this->nombre_es,
                $this->nombre_en,
                $this->id
            ));

            if ($stmt->rowCount() == 0) {
                return "iguales";
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail update accion: " . $e->getMessage(), 0);
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
    function getNombreEs()
    {
        return $this->nombre_es;
    }
    function getNombreEn()
    {
        return $this->nombre_en;
    }
    function getId()
    {
        return $this->id;
    }
    function setNombreEs($nombre_es)
    {
        $this->nombre_es = $nombre_es;
    }
    function setNombreEn($nombre_en)
    {
        $this->nombre_en = $nombre_en;
    }
    function setId($id)
    {
        $this->id = $id;
    }
}
