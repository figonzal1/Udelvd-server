<?php /** @noinspection ForgottenDebugOutputInspection */

/**
 * Objeto tipo entrevista
 */
class TipoEntrevista
{
    public function buscarTodos($conn)
    {

        try {
            $stmt = $conn->query(
                "SELECT * FROM tipo_entrevista"
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista tipos entrevistas: " . $e->getMessage(), 0);
            return false;
        }
    }
}
