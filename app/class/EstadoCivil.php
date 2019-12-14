<?php

/**
 * Objeto estado civil
 */

class EstadoCivil
{

    private $id;
    private $nombre;


    function buscarTodos($conn){

        try{

            $stmt = $conn ->query(
                "SELECT * FROM estado_civil"
            );
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $listado;
        }catch (PDOException $e) {
            error_log("Fail search lista estados civiles: " . $e->getMessage(), 0);
            return false;
        }
    }

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
