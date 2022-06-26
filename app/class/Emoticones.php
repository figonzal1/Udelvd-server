<?php


/**
 * Objeto emoticon
 */
class Emoticones
{

    private $id;
    private $url;
    private $descripcion;

    //! CARACTERISTICAS FUTURAS
    function agregar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO emoticon (url,descripcion) VALUES (?,?)"
            );

            $stmt->execute(
                array(
                    $this->url,
                    $this->descripcion
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
    function actualizar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "UPDATE emoticon SET url=?,descripcion=? WHERE id=?"
            );

            $stmt->execute(array(
                $this->url,
                $this->descripcion,
                $this->id
            ));

            if ($stmt->rowCount() == 0) {
                return "iguales";
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail update: " . $e->getMessage(), 0);
            return false;
        }
    }

    function buscarEmoticon($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * FROM emoticon WHERE id=?"
            );
            $stmt->execute(array($this->id));

            $emoticon = $stmt->fetch(PDO::FETCH_ASSOC);

            return $emoticon;
        } catch (PDOException $e) {
            error_log("Fail search emoticon: " . $e->getMessage(), 0);
            return false;
        }
    }

    function buscarTodos($conn)
    {
        try {

            $stmt = $conn->query(
                "SELECT * FROM emoticon"
            );
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $listado;
        } catch (PDOException $e) {
            error_log("Fail search lista emoticones: " . $e->getMessage(), 0);
            return false;
        }
    }

    //! CARACTERISTICAS FUTURAS
    function eliminar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "DELETE FROM emoticon WHERE id=?"
            );

            $stmt->execute(array($this->id));
            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail delete emoticon: ".$e->getMessage(),0);
            return false;
        }
    }


    function getId()
    {
        return $this->id;
    }
    function getUrl()
    {
        return $this->url;
    }
    function getDescripcion()
    {
        return $this->descripcion;
    }
    function setId($id)
    {
        $this->id = $id;
    }
    function setUrl($url)
    {
        $this->url = $url;
    }
    function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
}
