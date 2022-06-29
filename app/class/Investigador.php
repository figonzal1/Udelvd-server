<?php /** @noinspection ForgottenDebugOutputInspection */

require_once("Rol.php");

/**
 * Objeto investigador
 */
class Investigador
{

    private string $id;
    private string $nombre;
    private string $apellido;
    private string $email;
    private string $passwordHashed;
    private string $passwordRaw;

    private string $nombreRol; //Usado para crear investigadores

    private string $idRol;
    private string $activado;

    private int $limite = 10;

    public function agregar($conn)
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
            error_log("Fail insert: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($conn): bool
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

            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail update investigador: " . $e->getMessage());
            return false;
        }
    }


    public function buscarInvestigadorPorId($conn)
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
            i.proyecto,
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

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search investigador: " . $e->getMessage());
            return false;
        }
    }

    public function buscarInvestigadorPorEmail($conn)
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
                i.create_time,
                i.update_time,
                r.nombre AS nombre_rol
            FROM
                investigador i
            INNER JOIN rol r ON
                i.id_rol = r.id
            WHERE
                i.email = ?"
            );

            $stmt->execute(array($this->email));

            $investigador = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() === 1) {
                return $investigador;
            }

            return null;
        } catch (PDOException $e) {
            error_log("Fail search investigador: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPagina($conn, $pagina)
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

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista investigadores: " . $e->getMessage());
            return false;
        }
    }

    public function contarInvestigadores($conn)
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

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Fail conteo investigadores: " . $e->getMessage());
            return false;
        }
    }

    public function buscarTodos($conn)
    {

        try {

            //*FLUJO IF PARA LISTADO DE INVESTIGADORES SIN ADMIN Y SIN PROPIO USUARIO
            if ($this->id !== null) {
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
            } //*LISTADO GENERICO
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
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista investigadores: " . $e->getMessage());
            return false;
        }
    }

    //! Probablemente no será implementado
    public function eliminar($conn): bool
    {

        try {
            $stmt = $conn->prepare(
                "DELETE FROM investigador WHERE id=?"
            );

            $stmt->execute(array($this->id));
            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail delete investigador: " . $e->getMessage());
            return false;
        }
    }

    public function activar($conn): bool
    {
        try {

            $stmt = $conn->prepare(
                "UPDATE investigador SET activado=? WHERE id=?"
            );

            $stmt->execute(array(
                $this->activado,
                $this->id
            ));

            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail to activate investigador: " . $e->getMessage());
            return false;
        }
    }

    public function login($conn): bool
    {

        try {

            $stmt = $conn->prepare(
                "SELECT password from investigador WHERE email=?"
            );

            $stmt->execute(array($this->email));

            $passwordHash = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($this->passwordRaw, $passwordHash['password'])) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Fail to find hash: " . $e->getMessage());
            return false;
        }
    }


    public function resetPassword($conn): bool
    {
        try {

            $stmt = $conn->prepare(
                "UPDATE investigador SET password=? WHERE email=?"
            );

            $stmt->execute(array(
                $this->passwordHashed,
                $this->email
            ));

            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail to reset pass investigador: " . $e->getMessage());
            return false;
        }
    }


    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setNombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido): void
    {
        $this->apellido = $apellido;
    }

    public function setPassword($password): void
    {
        $this->passwordHashed = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function setIdRol($idRol): void
    {
        $this->idRol = $idRol;
    }

    public function setActivado($activado): void
    {
        $this->activado = $activado;
    }

    public function setPasswordRaw($passwordRaw): void
    {
        $this->passwordRaw = $passwordRaw;
    }

    public function setNombreRol($nombreRol): void
    {
        $this->nombreRol = $nombreRol;
    }
}
