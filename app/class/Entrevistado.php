<?php

/**
 * Objecto entrevistado
 */

class Entrevistado
{
    private $id;
    private $nombre;
    private $apellido;
    private $sexo;
    private $fecha_nac;
    private $ciudad;
    private $jubilado_legal;
    private $caidas;
    private $n_caidas;  //Opcional
    private $n_convivientes_3_meses;

    //Foreneas
    private $id_investigador;
    private $id_nivel_educacional; //Opcional
    private $id_estado_civil;
    private $id_conviviente;    //Opcional
    private $id_profesion;     //Opcional
    private $nombre_profesion;

    function agregar($conn)
    {
        try {

            //Intentar agregar profesion
            if ($this->nombre_profesion != NULL) {
                $profesion = new Profesion();
                $profesion->setNombre($this->nombre_profesion);

                $id_profesion = $profesion->agregar($conn);

                //Si ya existe profesion
                if (!$id_profesion || empty($id_profesion)) {
                    $profesion = $profesion->buscarProfesionPorNombre($conn);
                    $this->id_profesion = $profesion['id'];
                }
                //Si no existe profesion, guardar nuevo id
                else {
                    $this->id_profesion = $id_profesion;
                }
            } else {
                $id_profesion = NULL;
            }



            $stmt = $conn->prepare(
                "INSERT 
                INTO entrevistado 
                (nombre,
                apellido,
                sexo,
                fecha_nacimiento,
                ciudad,
                jubilado_legal,
                caidas,
                n_caidas,
                n_convivientes_3_meses,
                id_investigador,
                id_nivel_educacional,
                id_estado_civil,
                id_conviviente,
                id_profesion
                ) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
            );

            $stmt->execute(
                array(
                    $this->nombre,
                    $this->apellido,
                    $this->sexo,
                    $this->fecha_nac,
                    $this->ciudad,
                    $this->jubilado_legal,
                    $this->caidas,
                    $this->n_caidas,
                    $this->n_convivientes_3_meses,
                    $this->id_investigador,
                    $this->id_nivel_educacional,
                    $this->id_estado_civil,
                    $this->id_conviviente,
                    $this->id_profesion,
                )
            );

            //Consultar ultimo id
            $stmt = $conn->query("SELECT MAX(id) as id from entrevistado");
            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lastId['id'];
        } catch (PDOException $e) {
            error_log("Fail insert: " . $e->getMessage());
            return false;
        }
    }

    function actualizar($conn)
    {

        try {

            $stmt = $conn->prepare(
                "UPDATE 
                entrevistado 
                SET 
                nombre=?,
                apellido=?,
                sexo=?,
                fecha_nacimiento=?,
                ciudad=?,
                jubilado_legal=?,
                caidas=?,
                n_caidas=?,
                n_convivientes_3_meses=?,
                id_investigador=?,
                id_nivel_educacional=?,
                id_estado_civil=?,
                id_conviviente=?,
                id_profesion=? 
                WHERE id=?"
            );

            $stmt->execute(
                array(
                    $this->nombre,
                    $this->apellido,
                    $this->sexo,
                    $this->fecha_nac,
                    $this->ciudad,
                    $this->jubilado_legal,
                    $this->caidas,
                    $this->n_caidas,
                    $this->n_convivientes_3_meses,
                    $this->id_investigador,
                    $this->id_nivel_educacional,
                    $this->id_estado_civil,
                    $this->id_conviviente,
                    $this->id_profesion,
                    $this->id
                )
            );

            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail update: " . $e->getMessage());
            return false;
        }
    }

    function buscarEntrevistado($conn)
    {
        try {
            $stmt = $conn->prepare(
                "SELECT * FROM entrevistado WHERE id=?"
            );
            $stmt->execute(array($this->id));

            $entrevistado = $stmt->fetch(PDO::FETCH_ASSOC);

            return $entrevistado;
        } catch (PDOException $e) {
            error_log("Fail search entrevistado: " . $e->getMessage());
            return false;
        }
    }

    function buscarTodos($conn)
    {
        try {

            $stmt = $conn->query(
                "SELECT * FROM entrevistado"
            );
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $listado;
        } catch (PDOException $e) {
            error_log("Fail search lista entrevistados: " . $e->getMessage(), 0);
            return false;
        }
    }

    function eliminar($conn)
    {

        try {
            $stmt = $conn->prepare(
                "DELETE FROM entrevistado WHERE id=?"
            );

            $stmt->execute(array($this->id));
            if ($stmt->rowCount() == 0) {
                return false;
            } else if ($stmt->rowCount() == 1) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Fail delete entrevistado: " . $e->getMessage());
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
    function getFechaNac()
    {
        return $this->fecha_nac;
    }
    function getCiudad()
    {
        return $this->ciudad;
    }
    function getJubiladoLegal()
    {
        $this->jubilado_legal;
    }
    function getCaidas()
    {
        return $this->caidas;
    }
    function getNCaidas()
    {
        return $this->n_caidas;
    }
    function getNConvivientes()
    {
        return $this->n_convivientes_3_meses;
    }
    function getIdInvestigador()
    {
        return $this->id_investigador;
    }
    function getIdNivelEducacional()
    {
        return $this->id_nivel_educacional;
    }
    function getIdEstadoCivil()
    {
        return $this->id_estado_civil;
    }
    function getIdEnfermedad()
    {
        return $this->id_enfermedad;
    }
    function getIdConviviente()
    {
        return $this->id_conviviente;
    }
    function getIdProfesion()
    {
        return $this->id_profesion;
    }
    function getNombreProfesion()
    {
        return $this->nombre_profesion;
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
    function setFechaNac($fecha_nac)
    {
        $this->fecha_nac = $fecha_nac;
    }
    function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    }
    function setJubiladoLegal($jubilado_legal)
    {
        $this->jubilado_legal = $jubilado_legal;
    }
    function setCaidas($caidas)
    {
        $this->caidas = $caidas;
    }
    function setNCaidas($n_caidas)
    {
        $this->n_caidas = $n_caidas;
    }
    function setNConvivientes($n_convivientes_3_meses)
    {
        $this->n_convivientes_3_meses = $n_convivientes_3_meses;
    }
    function setIdInvestigador($id_investigador)
    {
        $this->id_investigador = $id_investigador;
    }
    function setIdNivelEducacional($id_nivel_educacional)
    {
        $this->id_nivel_educacional = $id_nivel_educacional;
    }
    function setIdEstadoCivil($id_estado_civil)
    {
        $this->id_estado_civil = $id_estado_civil;
    }
    function setIdEnfermedad($id_enfermedad)
    {
        $this->id_enfermedad = $id_enfermedad;
    }
    function setIdConviviente($id_conviviente)
    {
        $this->id_conviviente = $id_conviviente;
    }
    function setIdProfesion($id_profesion)
    {
        $this->id_profesion = $id_profesion;
    }
    function setNombreProfesion($nombre_profesion)
    {
        $this->nombre_profesion = $nombre_profesion;
    }
}
