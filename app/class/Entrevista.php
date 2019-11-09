<?php

/**
 * Objeto entrevista
 */

class Entrevista
{


    private $id;
    private $id_usuario;
    private $id_tipo_entrevista;
    private $fecha_entrevista;

    function agregar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO entrevista (id_usuario,id_tipo_entrevista,fecha_entrevista) VALUES (?,?,?)"
            );

            $stmt->execute(
                array(
                    $this->id_usuario,
                    $this->id_tipo_entrevista,
                    $this->fecha_entrevista
                )
            );

            //Consultar ultimo id
            $stmt = $conn->query("SELECT MAX(id) as id from entrevista");
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
                "UPDATE entrevista SET id_usuario=?,id_tipo_entrevista=?,fecha_entrevista=? WHERE id=?"
            );

            $stmt->execute(array(
                $this->id_usuario,
                $this->id_tipo_entrevista,
                $this->fecha_entrevista,
                $this->id
            ));

            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            echo "Fail update: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * Buscar entrevista por ID
     */
    function buscarEntrevista($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * FROM entrevista WHERE id=?"
            );
            $stmt->execute(array($this->id));

            $entrevista = $stmt->fetch(PDO::FETCH_ASSOC);

            return $entrevista;
        } catch (PDOException $e) {
            echo "Fail search entrevista: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * Buscar entrevistas por ID usuario
     */
    function buscarEntrevistasUsuario($conn){
        try {
            $stmt = $conn->prepare(
                "SELECT * FROM entrevista WHERE id_usuario=?"
            );
            $stmt->execute(array($this->id_usuario));

            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $listado;
        } catch (PDOException $e) {
            echo "Fail search entrevistas usuario: " . $e->getMessage() . "\n";
            return false;
        }
    }

    function buscarTodos($conn)
    {
        try {

            $stmt = $conn->query(
                "SELECT * FROM entrevista"
            );
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $listado;
        } catch (PDOException $e) {
            echo "Fail search lista entrevistas: " . $e->getMessage() . "\n";
            return false;
        }
    }

    function eliminar($conn)
    {

        try {
            $stmt = $conn->prepare(
                "DELETE FROM entrevista WHERE id=? AND id_usuario=?"
            );

            $stmt->execute(
                array(
                    $this->id,
                    $this->id_usuario
                )
            );
            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            echo "Fail delete entrevista: " . $e->getMessage() . "\n";
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
    function getIdUsuario()
    {
        return $this->id_usuario;
    }
    function getIdTipoEntrevista()
    {
        return $this->id_tipo_entrevista;
    }
    function getFechaEntrevista()
    {
        return $this->fecha_entrevista;
    }
    function setId($id)
    {
        $this->id = $id;
    }
    function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }
    function setIdTipoEntrevista($id_tipo_entrevista)
    {
        $this->id_tipo_entrevista = $id_tipo_entrevista;
    }
    function setFechaEntrevista($fecha_entrevista)
    {
        $this->fecha_entrevista = $fecha_entrevista;
    }
}
