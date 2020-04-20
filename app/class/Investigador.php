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

    private $limite = 10;

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
            error_log("Fail insert: " . $e->getMessage(), 0);
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
                return "iguales";
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail update investigador: " . $e->getMessage(), 0);
            return false;
        }
    }


    function buscarInvestigadorPorId($conn)
    {
        $sql = "SELECT
            i.id,
            i.nombre,
            i.apellido,
            i.email,
            i.id_rol,
            i.activado,
            i.create_time,
            i.update_time,
            r.id AS id_rol,
            r.nombre AS nombre_rol
        FROM
            investigador i
        INNER JOIN rol r ON
            i.id_rol = r.id
        WHERE
            i.id = ?";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($this->id));

            $investigador = $stmt->fetch(PDO::FETCH_ASSOC);

            return $investigador;
        } catch (PDOException $e) {
            error_log("Fail search investigador: " . $e->getMessage(), 0);
            return false;
        }
    }

    function buscarInvestigadorPorEmail($conn)
    {
        $sql = "SELECT
            i.id,
            i.nombre,
            i.apellido,
            i.email,
            i.id_rol,
            i.activado,
            i.create_time,
            i.update_time,
            r.nombre AS nombre_rol
        FROM
            investigador i
        INNER JOIN rol r ON
            i.id_rol = r.id
        WHERE
            i.email = ?";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($this->email));

            $investigador = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 1) {
                return $investigador;
            } else if ($stmt->rowCount() == 0) {
                return null;
            }
        } catch (PDOException $e) {
            error_log("Fail search investigador: " . $e->getMessage(), 0);
            return false;
        }
    }

    function buscarPagina($conn, $pagina)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT
                i.id,
                i.nombre,
                i.apellido,
                i.email,
                i.id_rol,
                i.activado,
                r.nombre AS nombre_rol,
                i.create_time
            FROM
                investigador i
            INNER JOIN rol r ON
                i.id_rol = r.id
            WHERE
                i.id <> :id_admin AND r.nombre LIKE 'In%'
            ORDER BY
                i.create_time
            DESC
            LIMIT :limite OFFSET :offset"
            );

            $stmt->bindValue(':id_admin', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':limite', $this->limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', ($pagina - 1) * $this->limite, PDO::PARAM_INT);
            $stmt->execute();

            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $listado;
        } catch (PDOException $e) {
            error_log("Fail search lista investigadores: " . $e->getMessage(), 0);
            return false;
        }
    }

    function contarInvestigadores($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT
                    COUNT(*)
                FROM
                    investigador i
                INNER JOIN rol r ON
                    i.id_rol = r.id
                WHERE
                    i.id <> ? AND r.nombre LIKE 'In%'"
            );
            $stmt->execute(array(
                $this->id
            ));

            $conteo = $stmt->fetchColumn();

            return $conteo;
        } catch (PDOException $e) {
            error_log("Fail conteo investigadores: " . $e->getMessage(), 0);
            return false;
        }
    }

    function buscarTodos($conn)
    {

        try {

            //*FLUJO IF PARA LISTADO DE INVESTIGADORES SIN ADMIN Y SIN PROPIO USUARIO
            if ($this->id != null) {
                $sql =
                    "SELECT
                        i.id,
                        i.nombre,
                        i.apellido,
                        i.email,
                        i.id_rol,
                        i.activado,
                        r.nombre AS nombre_rol,
                        i.create_time
                    FROM
                        investigador i
                    INNER JOIN rol r ON
                        i.id_rol = r.id
                    WHERE
                        i.id <> ? AND r.nombre LIKE 'In%'
                    ORDER BY
                        i.create_time
                    DESC";

                $stmt = $conn->prepare($sql);
                $stmt->execute(array($this->id));
            }
            //*LISTADO GENERICO
            else {
                $sql =
                    "SELECT
                    i.id,
                    i.nombre,
                    i.apellido,
                    i.email,
                    i.id_rol,
                    i.activado,
                    r.nombre AS nombre_rol,
                    i.create_time
                FROM
                    investigador i
                INNER JOIN rol r ON
                    i.id_rol = r.id";

                $stmt = $conn->query($sql);
            }
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $listado;
        } catch (PDOException $e) {
            error_log("Fail search lista investigadores: " . $e->getMessage(), 0);
            return false;
        }
    }

    //! Probablemente no será implementado
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
            error_log("Fail delete investigador: " . $e->getMessage(), 0);
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
                return "iguales";
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail to activate investigador: " . $e->getMessage(), 0);
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
            error_log("Fail to find hash: " . $e->getMessage(), 0);
            return false;
        }
    }


    function resetPassword($conn)
    {
        try {

            $stmt = $conn->prepare(
                "UPDATE investigador SET password=? WHERE email=?"
            );

            $stmt->execute(array(
                $this->passwordHashed,
                $this->email
            ));

            if ($stmt->rowCount() == 0) {
                return "iguales";
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail to reset pass investigador: " . $e->getMessage(), 0);
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
