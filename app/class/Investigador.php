<?php

require_once("Rol.php");
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
    private $passwordRaw;

    private $nombreRol; //Usado para crear investigadores

    private $idRol;
    private $activado;

    function agregar($conn)
    {
        try {
            //Buscar id rol por nombre
            $rol = new Rol();
            $rol->setNombre($this->nombreRol);
            $rol = $rol->buscarRolPorNombre($conn);
            $this->idRol = $rol['id'];

            //Insertar investigador con id Rol correcto
            $stmt = $conn->prepare(
                "INSERT INTO investigador (nombre,apellido,email,password,id_rol,activado) VALUES (?,?,?,?,?,?)"
            );

            $stmt->execute(
                array(
                    $this->nombre,
                    $this->apellido,
                    $this->email,
                    $this->passwordHashed,
                    $this->idRol,
                    $this->activado
                )
            );

            //Consultar ultimo id
            $stmt = $conn->query("SELECT MAX(id) as id from investigador");
            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lastId['id'];
        } catch (PDOException $e) {
            //echo "Fail insert: " . $e->getMessage() . "\n";
            error_log("Fail insert" . $e->getMessage(), 0);
            return false;
        }
    }

    function actualizar($conn)
    {
        try {
            $stmt = $conn->prepare(
                "UPDATE investigador SET nombre=?,apellido=?,email=?,password=?,id_rol=? WHERE id=?"
            );

            $stmt->execute(array(
                $this->nombre,
                $this->apellido,
                $this->email,
                $this->passwordHashed,
                $this->idRol,
                $this->id
            ));

            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            //echo "Fail update: " . $e->getMessage() . "\n";
            error_log("Fail update: " . $e->getMessage(), 0);
            return false;
        }
    }

    
    function buscarInvestigadorPorId($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT i.id,i.nombre,i.apellido,i.email,i.id_rol,i.activado,r.id as id_rol,r.nombre as nombre_rol FROM investigador i INNER JOIN rol r ON i.id_rol=r.id WHERE i.id=?"
            );
            $stmt->execute(array($this->id));

            $investigador = $stmt->fetch(PDO::FETCH_ASSOC);

            return $investigador;
        } catch (PDOException $e) {
            //echo "Fail search investigador: " . $e->getMessage() . "\n";
            error_log("Fail search investigador: " . $e->getMessage());
            return false;
        }
    }

    function buscarInvestigadorPorEmail($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT i.id,i.nombre,i.apellido,i.email,i.id_rol,i.activado,r.nombre as nombre_rol FROM investigador i INNER JOIN rol r ON i.id_rol=r.id WHERE i.email=?"
            );
            $stmt->execute(array($this->email));

            $investigador = $stmt->fetch(PDO::FETCH_ASSOC);

            return $investigador;
        } catch (PDOException $e) {
            //echo "Fail search investigador: " . $e->getMessage() . "\n";
            error_log("Fail search investigador: " . $e->getMessage());
            return false;
        }
    }

    function buscarTodos($conn)
    {
        try {

            $stmt = $conn->query(
                "SELECT i.id,i.nombre,i.apellido,i.email,i.id_rol,i.activado,r.nombre as nombre_rol FROM investigador i INNER JOIN rol r ON i.id_rol=r.id"
            );
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $listado;
        } catch (PDOException $e) {
            //echo "Fail search lista investigadores: " . $e->getMessage() . "\n";
            error_log("Fail search lista investigadores: " . $e->getMessage());
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
            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            //echo "Fail delete investigador: " . $e->getMessage() . "\n";
            error_log("Fail delete investigador: " . $e->getMessage());
            return false;
        }
    }

    function activar($conn)
    {

        try {

            $stmt = $conn->prepare(
                "UPDATE investigador SET activado=? WHERE id=?"
            );

            $stmt->execute(array(
                $this->activado,
                $this->id
            ));

            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            //echo "Fail to activate investigador: " . $e->getMessage() . "\n";
            error_log("Fail to activate investigador: " . $e->getMessage());
            return false;
        }
    }

    function login($conn)
    {

        try {

            $stmt = $conn->prepare(
                "SELECT password from investigador WHERE email=?"
            );

            $stmt->execute(array($this->email));

            $passwordHash = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($this->passwordRaw, $passwordHash['password'])) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            //echo "Fail to find hash: " . $e->getMessage() . "\n";
            error_log("Fail to find hash: " . $e->getMessage());
            return false;
        }
    }

    /**
     * GETTERS & SETTERS
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
    function getActivado()
    {
        return $this->activado;
    }
    function getNombreRol()
    {
        return $this->nombreRol;
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
    function setActivado($activado)
    {
        $this->activado = $activado;
    }
    function setPasswordRaw($passwordRaw)
    {
        $this->passwordRaw = $passwordRaw;
    }
    function setNombreRol($nombreRol)
    {
        $this->nombreRol = $nombreRol;
    }
}
