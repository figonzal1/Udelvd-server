<?php

/**
 * Objeto nivel educacional
 */
class NivelEducacional{

    private $id;
    private $nombre;

    function buscarTodos($conn){

        try{

            $stmt = $conn ->query(
                "SELECT * FROM nivel_educacional"
            );
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $listado;
        }catch (PDOException $e) {
            error_log("Fail search lista niveles educacionales: " . $e->getMessage(), 0);
            return false;
        }
    }

    /**
     * GETTERS & SETTERS
     */
    function getId(){
        return $this->id;
    }
    function getNombre(){
        return $this->nombre;
    }

    function setId($id){
        return $this->id = $id;
    }
    function setNombre($nombre){
        return $this->nombre = $nombre;
    }
}