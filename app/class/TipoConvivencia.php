<?php

/**
 * Objeto tipo convivenvia
 */

 class TipoConvivencia {

    private $id;
    private $nombre;


    function buscarTodos($conn){

        try{

            $stmt = $conn ->query(
                "SELECT * FROM tipo_convivencia"
            );
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $listado;
        }catch (PDOException $e) {
            error_log("Fail search lista tipos convivencia: " . $e->getMessage(), 0);
            return false;
        }
    }

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
        return $this->nombre=$nombre;
    }
 }