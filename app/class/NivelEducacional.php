<?php /** @noinspection ForgottenDebugOutputInspection */

/**
 * Objeto nivel educacional
 */
class NivelEducacional
{
    public function buscarTodos($conn)
    {

        try {

            return $conn->query("SELECT * FROM nivel_educacional")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista niveles educacionales: " . $e->getMessage(), 0);
            return false;
        }
    }
}