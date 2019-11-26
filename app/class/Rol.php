<?php


class Rol {

    private $id;
    private $nombre;



    function buscarRolPorNombre($conn){

        try{

            $stmt = $conn->prepare(
                "SELECT * FROM rol WHERE nombre=?"
            );

            $stmt -> execute(array($this->nombre));

            $rol = $stmt ->fetch(PDO::FETCH_ASSOC);

            return $rol;
        }catch(PDOException $e){
            echo "Fail search rol: ".$e->getMessage();
            return false;
        }
    }

    /**
     * GETTERS & SETTERS
     */
    function setId($id){
        $this->id=$id;
    }

    function setNombre($nombre){
        $this->nombre=$nombre;
    }

    function getId(){
        return $this->id;
    }

    function getNombre(){
        return $this->nombre;
    }
}
