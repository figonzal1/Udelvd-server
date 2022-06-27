<?php /** @noinspection ForgottenDebugOutputInspection */

/**
 * Objeto accion
 */
class Acciones
{

    private string $id;
    private string $nombreES;
    private string $nombreEN;


    public function agregar($conn)
    {
        $sql = "INSERT INTO accion (nombre_es,nombre_en) VALUES (?,?)";
        try {
            $stmt = $conn->prepare($sql);

            $stmt->execute(
                array(
                    $this->nombreES,
                    $this->nombreEN
                )
            );

            //Consultar ultimo id
            $stmt = $conn->query("SELECT MAX(id) as id from accion");
            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lastId['id'];
        } catch (PDOException $e) {
            error_log("Fail insert accion: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($conn): bool
    {
        $sql = "UPDATE accion SET nombre_es=?,nombre_en=? WHERE id=?";

        try {
            $stmt = $conn->prepare($sql);

            $stmt->execute(array(
                $this->nombreES,
                $this->nombreEN,
                $this->id
            ));
            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail update accion: " . $e->getMessage());
            return false;
        }
    }

    public function buscarAccion($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * FROM accion WHERE id=?"
            );
            $stmt->execute(array($this->id));

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search accion: " . $e->getMessage());
            return false;
        }
    }

    public function buscarTodos($conn)
    {
        try {

            return $conn->query("SELECT * FROM accion")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista acciones: " . $e->getMessage());
            return false;
        }
    }

    public function buscarTodosPorIdioma($conn, $idioma)
    {
        try {

            return $conn->query("SELECT * FROM accion ORDER BY nombre_" . $idioma)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista acciones por idioma: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar(object $conn, string $id_accion): bool
    {

        try {
            $stmt = $conn->prepare(
                "DELETE FROM accion WHERE id=?"
            );

            $stmt->execute(array($id_accion));
            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail delete accion: " . $e->getMessage());
            return false;
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setNombreES($nombreES): void
    {
        $this->nombreES = $nombreES;
    }

    public function setNombreEN($nombreEN): void
    {
        $this->nombreEN = $nombreEN;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }
}
