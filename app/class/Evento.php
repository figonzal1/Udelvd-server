<?php /** @noinspection ForgottenDebugOutputInspection */


/**
 * Objeto Evento
 */
class Evento
{

    private string $id;
    private string $idEntrevista;
    private string $idAccion;
    private string $idEmoticon;
    private string $justificacion;
    private string $horaEvento;


    public function agregar($conn)
    {
        try {

            $sql = "INSERT INTO evento (id_entrevista,id_accion,id_emoticon,justificacion,hora_evento) VALUES (?,?,?,?,?)";

            $stmt = $conn->prepare($sql);

            $stmt->execute(
                array(
                    $this->idEntrevista,
                    $this->idAccion,
                    $this->idEmoticon,
                    $this->justificacion,
                    $this->horaEvento
                )
            );

            //Consultar ultimo id
            $stmt = $conn->query("SELECT MAX(id) as id from evento");
            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);

            return $lastId['id'];
        } catch (PDOException $e) {
            error_log("Fail insert: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($conn): bool
    {
        try {

            $sql = "UPDATE evento SET id_entrevista=?,id_accion=?,id_emoticon=?,justificacion=?,hora_evento=? WHERE id=?";

            $stmt = $conn->prepare($sql);

            $stmt->execute(array(
                $this->idEntrevista,
                $this->idAccion,
                $this->idEmoticon,
                $this->justificacion,
                $this->horaEvento,
                $this->id
            ));

            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail update: " . $e->getMessage());
            return false;
        }
    }

    //*Buscar evento por id
    public function buscarEvento($conn)
    {
        try {

            $sql = "SELECT * FROM evento WHERE id=? AND id_entrevista=? AND visible=1";

            $stmt = $conn->prepare($sql);

            $stmt->execute(array(
                $this->id,
                $this->idEntrevista
            ));

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search evento: " . $e->getMessage());
            return false;
        }
    }

    //*Buscar evento de entrevista
    public function buscarEventosEntrevista($conn, $idioma)
    {
        try {

            $sql = "SELECT
                e.id,
                e.id_entrevista,
                e.id_accion,
                e.id_emoticon,
                e.justificacion,
                e.hora_evento,
                a.id AS id_accion_a,
                a.nombre_" . $idioma . " AS nombre_accion,
                em.id AS id_emoticon_e,
                em.url AS url_emoticon,
                em.descripcion_" . $idioma . " AS descripcion_emoticon
            FROM
                evento e
            INNER JOIN accion a ON
                e.id_accion = a.id
            INNER JOIN emoticon em ON
                e.id_emoticon = em.id
            WHERE
                id_entrevista = ?
            AND
                e.visible = 1
            ORDER BY e.hora_evento ASC";

            $stmt = $conn->prepare($sql);
            $stmt->execute(array($this->idEntrevista));

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista eventos: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($conn): bool
    {

        try {

            //PHISICAL DELETE
            //$stmt = $conn->prepare(
            //    "DELETE FROM evento WHERE id=?"
            //);

            //LOGICAL DELETE
            $sql = "UPDATE evento SET visible=0 WHERE id=?";
            $stmt = $conn->prepare($sql);

            $stmt->execute(
                array(
                    $this->id
                )
            );
            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail delete evento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $idEntrevista
     */
    public function setIdEntrevista(string $idEntrevista): void
    {
        $this->idEntrevista = $idEntrevista;
    }

    /**
     * @param string $idAccion
     */
    public function setIdAccion(string $idAccion): void
    {
        $this->idAccion = $idAccion;
    }

    /**
     * @param string $idEmoticon
     */
    public function setIdEmoticon(string $idEmoticon): void
    {
        $this->idEmoticon = $idEmoticon;
    }

    /**
     * @param string $justificacion
     */
    public function setJustificacion(string $justificacion): void
    {
        $this->justificacion = $justificacion;
    }

    /**
     * @param string $horaEvento
     */
    public function setHoraEvento(string $horaEvento): void
    {
        $this->horaEvento = $horaEvento;
    }
}
