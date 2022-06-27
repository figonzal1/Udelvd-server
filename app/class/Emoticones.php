<?php /** @noinspection ForgottenDebugOutputInspection */


/**
 * Objeto emoticon
 */
class Emoticones
{

    private string $id;
    private string $url;
    private string $descripcionES;
    private string $descripcionEN;

    //! CARACTERISTICAS FUTURAS
    public function agregar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO emoticon (url,descripcion_es,descripcion_en) VALUES (?,?,?)"
            );

            $stmt->execute(
                array(
                    $this->url,
                    $this->descripcionES,
                    $this->descripcionEN
                )
            );

            //Consultar ultimo id
            $stmt = $conn->query("SELECT MAX(id) as id from emoticon");
            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lastId['id'];
        } catch (PDOException $e) {
            error_log("Fail insert: " . $e->getMessage(), 0);
            return false;
        }
    }

    //! CARACTERISTICAS FUTURAS
    public function actualizar($conn): bool
    {
        try {
            $stmt = $conn->prepare(
                "UPDATE emoticon SET url=?,descripcion_es=?,descripcion_en=? WHERE id=?"
            );

            $stmt->execute(array(
                $this->url,
                $this->descripcionES,
                $this->descripcionEN,
                $this->id
            ));

            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail update: " . $e->getMessage(), 0);
            return false;
        }
    }

    public function buscarEmoticon($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * FROM emoticon WHERE id=?"
            );
            $stmt->execute(array($this->id));

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search emoticon: " . $e->getMessage(), 0);
            return false;
        }
    }

    public function buscarTodos($conn)
    {
        try {

            return $conn->query("SELECT * FROM emoticon")->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista emoticones: " . $e->getMessage(), 0);
            return false;
        }
    }

    //! CARACTERISTICAS FUTURAS
    public function eliminar($conn): bool
    {
        try {
            $stmt = $conn->prepare(
                "DELETE FROM emoticon WHERE id=?"
            );

            $stmt->execute(array($this->id));
            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail delete emoticon: " . $e->getMessage(), 0);
            return false;
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

}
