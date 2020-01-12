<?php


/**
 * Objeto Evento
 */
class Evento
{

    private $id;
    private $id_entrevista;
    private $id_accion;
    private $id_emoticon;
    private $justificacion;
    private $hora_evento;


    function agregar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO evento (id_entrevista,id_accion,id_emoticon,justificacion,hora_evento) VALUES (?,?,?,?,?)"
            );

            $stmt->execute(
                array(
                    $this->id_entrevista,
                    $this->id_accion,
                    $this->id_emoticon,
                    $this->justificacion,
                    $this->hora_evento
                )
            );

            //Consultar ultimo id
            $stmt = $conn->query("SELECT MAX(id) as id from evento");
            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lastId['id'];
        } catch (PDOException $e) {
            error_log("Fail insert: " . $e->getMessage(), 0);
            return false;
        }
    }

    function actualizar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "UPDATE evento SET id_entrevista=?,id_accion=?,id_emoticon=?,justificacion=?,hora_evento=? WHERE id=?"
            );

            $stmt->execute(array(
                $this->id_entrevista,
                $this->id_accion,
                $this->id_emoticon,
                $this->justificacion,
                $this->hora_evento,
                $this->id
            ));

            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail update: " . $e->getMessage(), 0);
            return false;
        }
    }

    /**
     * Buscar evento por id
     */
    function buscarEvento($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * FROM evento WHERE id=?"
            );
            $stmt->execute(array($this->id));

            $evento = $stmt->fetch(PDO::FETCH_ASSOC);

            return $evento;
        } catch (PDOException $e) {
            error_log("Fail search evento: " . $e->getMessage(), 0);
            return false;
        }
    }

    /**
     * Buscar evento de entrevista
     */
    function buscarEventosEntrevista($conn)
    {
        try {

            $stmt = $conn->prepare(
                "SELECT
                e.id,
                e.id_entrevista,
                e.id_accion,
                e.id_emoticon,
                e.justificacion,
                e.hora_evento,
                a.id AS id_accion_a,
                a.nombre AS nombre_accion,
                em.id AS id_emoticon_e,
                em.url AS url_emoticon,
                em.descripcion AS descripcion_emoticon
            FROM
                evento e
            INNER JOIN accion a ON
                e.id_accion = a.id
            INNER JOIN emoticon em ON
                e.id_emoticon = em.id
            WHERE
                id_entrevista = ?
            ORDER BY e.hora_evento ASC"
            );
            $stmt->execute(array($this->id_entrevista));

            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $listado;
        } catch (PDOException $e) {
            error_log("Fail search lista eventos: " . $e->getMessage(), 0);
            return false;
        }
    }

    function eliminar($conn)
    {

        try {
            $stmt = $conn->prepare(
                "DELETE FROM evento WHERE id=?"
            );

            $stmt->execute(array($this->id));
            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail delete evento: " . $e->getMessage(), 0);
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
    function getIdEntrevista()
    {
        return $this->id_entrevista;
    }
    function getIdAccion()
    {
        return $this->id_accion;
    }
    function getIdEmoticon()
    {
        return $this->id_emoticon;
    }
    function getJustificacion()
    {
        return $this->justificacion;
    }
    function getHoraEvento()
    {
        return $this->hora_evento;
    }
    function setId($id)
    {
        $this->id = $id;
    }
    function setIdEntrevista($id_entrevista)
    {
        $this->id_entrevista = $id_entrevista;
    }
    function setIdAccion($id_accion)
    {
        $this->id_accion = $id_accion;
    }
    function setIdEmoticon($id_emoticon)
    {
        $this->id_emoticon = $id_emoticon;
    }
    function setJustificacion($justificacion)
    {
        $this->justificacion = $justificacion;
    }
    function setHoraEvento($hora_evento)
    {
        $this->hora_evento = $hora_evento;
    }
}
