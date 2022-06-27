<?php /** @noinspection ForgottenDebugOutputInspection */

/**
 * Objeto entrevista
 */
class Entrevista
{
    private string $id;
    private string $idEntrevistado;
    private string $idTipoEntrevista;
    private string $fechaEntrevista;

    public function agregar($conn)
    {
        try {

            $sql = "INSERT INTO entrevista (id_entrevistado,id_tipo_entrevista,fecha_entrevista) VALUES (?,?,?)";

            $stmt = $conn->prepare($sql);

            $stmt->execute(
                array(
                    $this->idEntrevistado,
                    $this->idTipoEntrevista,
                    $this->fechaEntrevista
                )
            );

            //Consultar ultimo id
            $stmt = $conn->query("SELECT MAX(id) as id from entrevista");
            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);

            return $lastId['id'];
        } catch (PDOException $e) {
            error_log("Fail insert entrevista: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($conn): bool
    {
        try {

            $sql = "UPDATE entrevista SET id_entrevistado=?,id_tipo_entrevista=?,fecha_entrevista=? WHERE id=?";

            $stmt = $conn->prepare($sql);

            $stmt->execute(array(
                $this->idEntrevistado,
                $this->idTipoEntrevista,
                $this->fechaEntrevista,
                $this->id
            ));

            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail update entrevista: " . $e->getMessage());
            return false;
        }
    }

    //* Buscar entrevista por ID
    public function buscarEntrevista($conn)
    {
        try {

            $sql = "SELECT * FROM entrevista WHERE id=? AND visible=1";

            $stmt = $conn->prepare($sql);

            $stmt->execute(
                array(
                    $this->id
                )
            );

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search entrevista: " . $e->getMessage());
            return false;
        }
    }

    //* Buscar entrevistas de una persona
    public function buscarEntrevistasPersonales($conn, $idioma)
    {
        try {

            $sql = "SELECT
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
            DESC";

            $stmt = $conn->prepare($sql);

            $stmt->execute(
                array(
                    $this->idEntrevistado
                )
            );

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search entrevistas usuarios: " . $e->getMessage());
            return false;
        }
    }

    //* Buscar una entrevista de una persona
    public function buscarEntrevistaPersonal($conn)
    {
        try {

            $sql = "SELECT * FROM entrevista WHERE id=? AND id_entrevistado=? AND visible=1";

            $stmt = $conn->prepare($sql);

            $stmt->execute(
                array(
                    $this->id,
                    $this->idEntrevistado
                )
            );

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search entrevista: " . $e->getMessage());
            return false;
        }
    }

    public function buscarTodos($conn)
    {
        try {

            $sql = "SELECT * FROM entrevista WHERE visible=1 ORDER BY fecha_entrevista";

            return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista entrevistas: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($conn): bool
    {

        try {

            //PYSHICAL DELETE
            //$stmt = $conn->prepare(
            //    "DELETE FROM entrevista WHERE id=? AND id_entrevistado=?"
            //);

            //LOGICAL DELETE

            $sql = "UPDATE entrevista SET visible=0 WHERE id=? AND id_entrevistado=?";

            $stmt = $conn->prepare($sql);

            $stmt->execute(
                array(
                    $this->id,
                    $this->idEntrevistado
                )
            );

            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail delete entrevista: " . $e->getMessage());
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
     * @param string $idEntrevistado
     */
    public function setIdEntrevistado(string $idEntrevistado): void
    {
        $this->idEntrevistado = $idEntrevistado;
    }

    /**
     * @param string $idTipoEntrevista
     */
    public function setIdTipoEntrevista(string $idTipoEntrevista): void
    {
        $this->idTipoEntrevista = $idTipoEntrevista;
    }

    /**
     * @param string $fechaEntrevista
     */
    public function setFechaEntrevista(string $fechaEntrevista): void
    {
        $this->fechaEntrevista = $fechaEntrevista;
    }
}
