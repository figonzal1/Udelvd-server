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
    private $jubilado_legal;
    private $caidas;
    private $n_caidas;  //Opcional
    private $n_convivientes_3_meses;

    //Foreneas
    private $id_investigador;
    private $id_ciudad;
    private $nombre_ciudad;
    private $id_nivel_educacional; //Opcional
    private $id_estado_civil;
    private $id_tipo_convivencia;    //Opcional
    private $id_profesion;     //Opcional
    private $nombre_profesion;

    function agregar($conn)
    {
        try {

            //Intentar agregar profesion
            if ($this->nombre_profesion != NULL) {
                $profesion = new Profesion();
                $profesion->setNombre($this->nombre_profesion);
                $existente = $profesion->buscarProfesionPorNombre($conn);

                //Si no existe, agrega nuevo
                if (!$existente) {
                    $this->id_profesion = $profesion->agregar($conn);
                }
                //Si existe asignar id
                else {
                    $this->id_profesion = $existente['id'];
                }
            } else {
                $this->id_profesion = NULL;
            }

            //Intentar agregar ciudad
            if ($this->nombre_ciudad != NULL) {

                $ciudad = new Ciudad();
                $ciudad->setNombre($this->nombre_ciudad);
                $existente = $ciudad->buscarCiudadPorNombre($conn);

                //Si no existe, agrega nuevo
                if (!$existente) {
                    $this->id_ciudad = $ciudad->agregar($conn);
                }

                //Si existe asignar id
                else {
                    $this->id_ciudad = $existente['id'];
                }
            } else {
                $this->id_ciudad = NULL;
            }

            $stmt = $conn->prepare(
                "INSERT 
                INTO entrevistado 
                (nombre,
                apellido,
                sexo,
                fecha_nacimiento,
                jubilado_legal,
                caidas,
                n_caidas,
                n_convivientes_3_meses,
                id_investigador,
                id_ciudad,
                id_nivel_educacional,
                id_estado_civil,
                id_tipo_convivencia,
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
                    $this->jubilado_legal,
                    $this->caidas,
                    $this->n_caidas,
                    $this->n_convivientes_3_meses,
                    $this->id_investigador,
                    $this->id_ciudad,
                    $this->id_nivel_educacional,
                    $this->id_estado_civil,
                    $this->id_tipo_convivencia,
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

            //Intentar agregar profesion
            if ($this->nombre_profesion != NULL) {
                $profesion = new Profesion();
                $profesion->setNombre($this->nombre_profesion);
                $existente = $profesion->buscarProfesionPorNombre($conn);

                //Si no existe, agrega nuevo
                if (!$existente) {
                    $this->id_profesion = $profesion->agregar($conn);
                }
                //Si existe asignar id
                else {
                    $this->id_profesion = $existente['id'];
                }
            } else {
                $this->id_profesion = NULL;
            }

            //Intentar agregar ciudad
            if ($this->nombre_ciudad != NULL) {

                $ciudad = new Ciudad();
                $ciudad->setNombre($this->nombre_ciudad);
                $existente = $ciudad->buscarCiudadPorNombre($conn);

                //Si no existe, agrega nuevo
                if (!$existente) {
                    $this->id_ciudad = $ciudad->agregar($conn);
                }

                //Si existe asignar id
                else {
                    $this->id_ciudad = $existente['id'];
                }
            } else {
                $this->id_ciudad = NULL;
            }

            $stmt = $conn->prepare(
                "UPDATE 
                entrevistado 
                SET 
                nombre=?,
                apellido=?,
                sexo=?,
                fecha_nacimiento=?,
                jubilado_legal=?,
                caidas=?,
                n_caidas=?,
                n_convivientes_3_meses=?,
                id_investigador=?,
                id_ciudad=?,
                id_nivel_educacional=?,
                id_estado_civil=?,
                id_tipo_convivencia=?,
                id_profesion=? 
                WHERE id=?"
            );

            $stmt->execute(
                array(
                    $this->nombre,
                    $this->apellido,
                    $this->sexo,
                    $this->fecha_nac,
                    $this->jubilado_legal,
                    $this->caidas,
                    $this->n_caidas,
                    $this->n_convivientes_3_meses,
                    $this->id_investigador,
                    $this->id_ciudad,
                    $this->id_nivel_educacional,
                    $this->id_estado_civil,
                    $this->id_tipo_convivencia,
                    $this->id_profesion,
                    $this->id
                )
            );

            if ($stmt->rowCount() == 0) {
                return "iguales";
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
                "SELECT
                id,
                nombre,
                apellido,
                sexo,
                fecha_nacimiento,
                jubilado_legal,
                caidas,
                n_caidas,
                n_convivientes_3_meses,
                id_investigador,
                id_ciudad,
                id_nivel_educacional,
                id_estado_civil,
                id_tipo_convivencia,
                id_profesion,
                create_time,
                update_time,
                (SELECT COUNT(*) FROM entrevista WHERE id_entrevistado = ?) AS n_entrevistas
            FROM
                entrevistado
            WHERE
                id = ?"
            );
            $stmt->execute(
                array(
                    $this->id,
                    $this->id
                )
            );

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
                "SELECT
                eo.id,
                eo.nombre,
                eo.apellido,
                eo.fecha_nacimiento,
                eo.jubilado_legal,
                eo.caidas,
                eo.sexo,
                eo.n_caidas,
                eo.n_convivientes_3_meses,
                eo.id_investigador,
                eo.id_ciudad,
                eo.id_nivel_educacional,
                eo.id_estado_civil,
                eo.id_tipo_convivencia,
                eo.id_profesion,
                eo.create_time,
                eo.update_time,
                (SELECT COUNT(*) FROM entrevista WHERE id_entrevistado = eo.id) AS n_entrevistas
            FROM
                entrevistado eo
            ORDER BY eo.create_time DESC"
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
    function getNombreCiudad()
    {
        return $this->nombre_ciudad;
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
    function getIdCiudad()
    {
        return $this->id_ciudad;
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
    function getIdTipoConvivencia()
    {
        return $this->id_tipo_convivencia;
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
    function setNombreCiudad($nombre_ciudad)
    {
        $this->nombre_ciudad = $nombre_ciudad;
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
    function setIdCiudad($id_ciudad)
    {
        $this->id_ciudad = $id_ciudad;
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
    function setIdTipoConvivencia($id_tipo_convivencia)
    {
        $this->id_tipo_convivencia = $id_tipo_convivencia;
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
