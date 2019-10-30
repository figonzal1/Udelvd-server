<?php

/**
 * Objecto usuario
 */

class Usuario
{
    private $id;
    private $nombre;
    private $apellido;
    private $sexo;
    private $edad;
    private $ciudad;
    private $id_investigador;


    function agregar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO usuario (nombre,apellido,sexo,edad,ciudad,id_investigador) VALUES (?,?,?,?,?,?)"
            );

            $stmt->execute(
                array(
                    $this->nombre,
                    $this->apellido,
                    $this->sexo,
                    $this->edad,
                    $this->ciudad,
                    $this->id_investigador
                )
            );

            $lastId = $conn->lastInsertId();
            return $lastId;
        } catch (PDOException $e) {
            echo "Fail insert: " . $e->getMessage() . "\n";
            return false;
        }
    }

    function actualizar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "UPDATE usuario SET nombre=?,apellido=?,sexo=?,edad=?,ciudad=?,id_investigador=? WHERE id=?"
            );

            $stmt->execute(array(
                $this->nombre,
                $this->apellido,
                $this->sexo,
                $this->edad,
                $this->ciudad,
                $this->id_investigador,
                $this->id
            ));

            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            echo "Fail update: " . $e->getMessage() . "\n";
            return false;
        }
    }


    function buscarUsuario($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * FROM usuario WHERE id=?"
            );
            $stmt->execute(array($this->id));

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            return $usuario;
        } catch (PDOException $e) {
            echo "Fail search usuario: " . $e->getMessage() . "\n";
            return false;
        }
    }

    function buscarTodos($conn)
    {
        try {

            $stmt = $conn->query(
                "SELECT * FROM usuario"
            );
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $listado;
        } catch (PDOException $e) {
            echo "Fail search lista usuarios: " . $e->getMessage() . "\n";
            return false;
        }
    }

    function eliminar($conn)
    {

        try {
            $stmt = $conn->prepare(
                "DELETE FROM usuario WHERE id=?"
            );

            $stmt->execute(array($this->id));
            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            echo "Fail delete usuario: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * GETERS & SETTERS
     */
    function getId()
    {
        return $this->id;
    }
    function getNombre()
    {
        return $this->nombre;
    }
    function getApellido()
    {
        return $this->apellido;
    }
    function getSexo()
    {
        return $this->sexo;
    }
    function getEdad()
    {
        return $this->edad;
    }
    function getCiudad()
    {
        return $this->ciudad;
    }
    function getIdInvestigador()
    {
        return $this->id_investigador;
    }
    function setId($id)
    {
        $this->id = $id;
    }
    function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }
    function setSexo($sexo)
    {
        $this->sexo = $sexo;
    }
    function setEdad($edad)
    {
        $this->edad = $edad;
    }
    function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    }
    function setIdInvestigador($id_investigador)
    {
        $this->id_investigador = $id_investigador;
    }
}
