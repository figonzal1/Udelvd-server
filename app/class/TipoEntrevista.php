<?php

/**
 * Objeto tipo entrevista
 */

class TipoEntrevista
{

    private $id;
    private $nombre;

    function buscarTodos($conn)
    {

        try {
            $stmt = $conn->query(
                "SELECT * FROM tipo_entrevista"
            );
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $listado;
        } catch (PDOException $e) {
            error_log("Fail search lista tipos entrevistas: " . $e->getMessage(), 0);
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
