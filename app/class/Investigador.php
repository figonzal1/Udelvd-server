<?php

/**
 * Objeto investigador
 */

class Investigador
{

    private $id;
    private $nombre;
    private $apellido;
    private $email;
    private $passwordHashed;
    private $idRol;

    function crear($conn)
    {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO investigador (nombre,apellido,email,password,id_rol) VALUES (?,?,?,?,?)"
            );

            $stmt->execute(array(
                $this->nombre,
                $this->apellido,
                $this->email,
                $this->passwordHashed,
                $this->id_rol
            ));

            return true;
        } catch (PDOException $e) {
            echo "Fail insert: " . $e->getMessage() . "\n";
            return false;
        }
    }

    function actualizar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "UPDATE investigador SET nombre=?,apellido=?,password=?,id_rol=? WHERE id=?"
            );

            $stmt->execute(array(
                $this->nombre,
                $this->apellido,
                $this->passwordHash,
                $this->id_rol,
                $this->id
            ));

            return true;
        } catch (PDOException $e) {
            echo "Fail update: " . $e->getMessage() . "\n";
            return false;
        }
    }

    function buscarInvestigador($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * FROM investigador WHERE id=?"
            );
            $stmt->execute(array($this->id));

            $investigador = $stmt->fetch(PDO::FETCH_ASSOC);

            return $investigador;
        } catch (PDOException $e) {
            echo "Fail search investigador: " . $e->getMessage() . "\n";
            return false;
        }
    }

    function buscarTodos($conn)
    {
        try {

            $stmt = $conn->query(
                "SELECT * FROM investigador"
            );
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $listado;
        } catch (PDOException $e) {
            echo "Fail search lista investigadores: " . $e->getMessage() . "\n";
            return false;
        }
    }

    function eliminar($conn)
    {

        try {
            $stmt = $conn->prepare(
                "DELETE FROM investigador WHERE id=?"
            );

            $stmt->execute(array($this->id));
            return true;
        } catch (PDOException $e) {
            echo "Fail delete investigador: " . $e->getMessage() . "\n";
            return false;
        }
    }

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
    function getPassword()
    {
        return $this->passwordHash;
    }
    function getEmail()
    {
        return $this->email;
    }
    function getIdRol()
    {
        return $this->idRol;
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
    function setPassword($password)
    {
        $this->passwordHashed = password_hash($password, PASSWORD_DEFAULT);
    }
    function setEmail($email)
    {
        $this->email = $email;
    }
    function setIdRol($idRol)
    {
        $this->idRol = $idRol;
    }
}
