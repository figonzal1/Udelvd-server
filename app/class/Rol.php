<?php /** @noinspection ForgottenDebugOutputInspection */


class Rol
{

    private string $id;
    private string $nombre;

    public function buscarRolPorNombre($conn)
    {

        try {

            $stmt = $conn->prepare(
                "SELECT * FROM rol WHERE nombre=?"
            );

            $stmt->execute(array($this->nombre));

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search rol:" . $e->getMessage(), 0);
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
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
}
