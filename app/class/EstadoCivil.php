<?php /** @noinspection ForgottenDebugOutputInspection */

/**
 * Objeto estado civil
 */
class EstadoCivil
{

    public function buscarTodos($conn)
    {

        try {

            return $conn->query("SELECT * FROM estado_civil")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista estados civiles: " . $e->getMessage(), 0);
            return false;
        }
    }
}
