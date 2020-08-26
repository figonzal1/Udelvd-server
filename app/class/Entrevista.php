<?php

/**
 * Objeto entrevista
 */

class Entrevista
{


    private $id;
    private $id_entrevistado;
    private $id_tipo_entrevista;
    private $fecha_entrevista;

    function agregar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO entrevista (id_entrevistado,id_tipo_entrevista,fecha_entrevista) VALUES (?,?,?)"
            );

            $stmt->execute(
                array(
                    $this->id_entrevistado,
                    $this->id_tipo_entrevista,
                    $this->fecha_entrevista
                )
            );

            //Consultar ultimo id
            $stmt = $conn->query("SELECT MAX(id) as id from entrevista");
            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lastId['id'];
        } catch (PDOException $e) {
            error_log("Fail insert entrevista: " . $e->getMessage(), 0);
            return false;
        }
    }

    function actualizar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "UPDATE entrevista SET id_entrevistado=?,id_tipo_entrevista=?,fecha_entrevista=? WHERE id=?"
            );

            $stmt->execute(array(
                $this->id_entrevistado,
                $this->id_tipo_entrevista,
                $this->fecha_entrevista,
                $this->id
            ));

            if ($stmt->rowCount() == 0) {
                return "iguales";
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail update entrevista: " . $e->getMessage(), 0);
            return false;
        }
    }

    //* Buscar entrevista por ID
    function buscarEntrevista($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * FROM entrevista WHERE id=? AND visible=1"
            );
            $stmt->execute(
                array(
                    $this->id
                )
            );

            $entrevista = $stmt->fetch(PDO::FETCH_ASSOC);

            return $entrevista;
        } catch (PDOException $e) {
            error_log("Fail search entrevista: " . $e->getMessage(), 0);
            return false;
        }
    }

    //* Buscar entrevistas de una persona
    function buscarEntrevistasPersonales($conn, $idioma)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT
                    e.id,
                    e.id_entrevistado,
                    e.id_tipo_entrevista,
                    e.fecha_entrevista,
                    t.id as id_tipo_entrevista,
                    t.nombre_" . $idioma . " as nombre_tipo_entrevista
                FROM
                    entrevista e
                INNER JOIN tipo_entrevista t ON
                    t.id = e.id_tipo_entrevista
                WHERE
                    e.id_entrevistado = ?
                AND
                    e.visible = 1
                ORDER BY
                    e.fecha_entrevista
                DESC"
            );
            $stmt->execute(
                array(
                    $this->id_entrevistado
                )
            );

            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $listado;
        } catch (PDOException $e) {
            error_log("Fail search entrevistas usuarios: " . $e->getMessage(), 0);
            return false;
        }
    }

    //* Buscar una entrevista de una persona
    function buscarEntrevistaPersonal($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * FROM entrevista WHERE id=? AND id_entrevistado=? AND visible=1"
            );
            $stmt->execute(
                array(
                    $this->id,
                    $this->id_entrevistado
                )
            );

            $entrevista = $stmt->fetch(PDO::FETCH_ASSOC);

            return $entrevista;
        } catch (PDOException $e) {
            error_log("Fail search entrevista: " . $e->getMessage(), 0);
            return false;
        }
    }

    function buscarTodos($conn)
    {
        try {

            $stmt = $conn->query(
                "SELECT * FROM entrevista WHERE visible=1 ORDER BY fecha_entrevista"
            );
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $listado;
        } catch (PDOException $e) {
            error_log("Fail search lista entrevistas: " . $e->getMessage(), 0);
            return false;
        }
    }

    function eliminar($conn)
    {

        try {

            //PYSHICAL DELETE
            //$stmt = $conn->prepare(
            //    "DELETE FROM entrevista WHERE id=? AND id_entrevistado=?"
            //);

            //LOGICAL DELETE
            $stmt = $conn->prepare(
                "UPDATE entrevista SET visible=? WHERE id=? AND id_entrevistado=?"
            );

            $stmt->execute(
                array(
                    0,
                    $this->id,
                    $this->id_entrevistado
                )
            );
            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail delete entrevista: " . $e->getMessage(), 0);
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
    function getIdEntrevistado()
    {
        return $this->id_entrevistado;
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
    function setIdEntrevistado($id_entrevistado)
    {
        $this->id_entrevistado = $id_entrevistado;
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
