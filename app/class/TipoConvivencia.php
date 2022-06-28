<?php /** @noinspection ForgottenDebugOutputInspection */

/**
 * Objeto tipo convivencia
 */
class TipoConvivencia
{
    public function buscarTodos($conn)
    {
        try {

            $stmt = $conn->query(
                "SELECT * FROM tipo_convivencia"
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista tipos convivencia: " . $e->getMessage());
            return false;
        }
    }
}