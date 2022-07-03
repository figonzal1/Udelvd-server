<?php /** @noinspection ForgottenDebugOutputInspection */

/**
 * Objecto entrevistado
 */
class Entrevistado
{
    private string $id;
    private string $nombre;
    private string $apellido;
    private string $sexo;
    private string $fechaNac;
    private string $jubiladoLegal;
    private string $caidas;
    private ?string $nCaidas;  //Opcional
    private string $nConvivientes3meses;

    //Foreneas
    private string $idInvestigador;

    private ?string $idCiudad;
    private string $nombreCiudad;
    private ?string $idNivelEducacional; //Opcional
    private string $idEstadoCivil;
    private ?string $idTipoConvivencia;    //Opcional

    private ?string $idProfesion;
    private ?string $nombreProfesion;

    private int $limite = 10;

    public function agregar($conn)
    {
        try {

            //Intentar agregar profesion
            if (!empty($this->nombreProfesion)) {

                $profesion = new Profesion();
                $profesion->setNombre($this->nombreProfesion);

                $existente = $profesion->buscarProfesionPorNombre($conn);

                //Si no existe, agrega nuevo
                if (!$existente) {
                    $this->idProfesion = $profesion->agregar($conn);
                } //Si existe asignar id
                else {
                    $this->idProfesion = $existente['id'];
                }
            } else {
                $this->idProfesion = NULL;
            }

            //Intentar agregar ciudad
            if (!empty($this->nombreCiudad)) {

                $ciudad = new Ciudad();
                $ciudad->setNombre($this->nombreCiudad);

                $existente = $ciudad->buscarCiudadPorNombre($conn);

                //Si no existe, agrega nuevo
                if (!$existente) {
                    $this->idCiudad = $ciudad->agregar($conn);
                } //Si existe asignar id
                else {
                    $this->idCiudad = $existente['id'];
                }
            } else {
                $this->idCiudad = NULL;
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
                    $this->fechaNac,
                    $this->jubiladoLegal,
                    $this->caidas,
                    $this->nCaidas,
                    $this->nConvivientes3meses,
                    $this->idInvestigador,
                    $this->idCiudad,
                    $this->idNivelEducacional,
                    $this->idEstadoCivil,
                    $this->idTipoConvivencia,
                    $this->idProfesion,
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

    public function actualizar($conn): bool
    {

        try {

            //Intentar agregar profesion
            if (!empty($this->nombreProfesion)) {

                $profesion = new Profesion();
                $profesion->setNombre($this->nombreProfesion);

                $existente = $profesion->buscarProfesionPorNombre($conn);

                //Si no existe, agrega nuevo
                if (!$existente) {
                    $this->idProfesion = $profesion->agregar($conn);
                } //Si existe asignar id
                else {
                    $this->idProfesion = $existente['id'];
                }
            } else {
                $this->idProfesion = NULL;
            }

            //Intentar agregar ciudad
            if (!empty($this->nombreCiudad)) {

                $ciudad = new Ciudad();
                $ciudad->setNombre($this->nombreCiudad);
                $existente = $ciudad->buscarCiudadPorNombre($conn);

                //Si no existe, agrega nuevo
                if (!$existente) {
                    $this->idCiudad = $ciudad->agregar($conn);
                } //Si existe asignar id
                else {
                    $this->idCiudad = $existente['id'];
                }
            } else {
                $this->idCiudad = NULL;
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
                WHERE
                    id=?"
            );

            $stmt->execute(
                array(
                    $this->nombre,
                    $this->apellido,
                    $this->sexo,
                    $this->fechaNac,
                    $this->jubiladoLegal,
                    $this->caidas,
                    $this->nCaidas,
                    $this->nConvivientes3meses,
                    $this->idInvestigador,
                    $this->idCiudad,
                    $this->idNivelEducacional,
                    $this->idEstadoCivil,
                    $this->idTipoConvivencia,
                    $this->idProfesion,
                    $this->id
                )
            );

            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail update: " . $e->getMessage());
            return false;
        }
    }

    public function buscarEntrevistado($conn)
    {
        try {

            $sql = "SELECT
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
                (SELECT COUNT(*) FROM entrevista WHERE id_entrevistado = ? AND visible=1) AS n_entrevistas
            FROM
                entrevistado
            WHERE
                id = ?
            AND
                visible = 1";

            $stmt = $conn->prepare($sql);

            $stmt->execute(
                array(
                    $this->id,
                    $this->id
                )
            );

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search entrevistado: " . $e->getMessage());
            return false;
        }
    }

    public function buscarTodosConPagina($conn, $pagina)
    {
        try {

            $sql = "SELECT
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
                i.nombre As nombre_investigador,
                i.apellido AS apellido_investigador,
                (SELECT COUNT(*) FROM entrevista WHERE id_entrevistado = eo.id AND visible=1) AS n_entrevistas
            FROM
                entrevistado eo
            INNER JOIN investigador i
            ON i.id=eo.id_investigador
            WHERE
                eo.visible = 1
            ORDER BY eo.create_time DESC 
            LIMIT :limite OFFSET :offset";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':limite', $this->limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', ($pagina - 1) * $this->limite, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista entrevistados totales: " . $e->getMessage());
            return false;
        }
    }

    public function buscarEntrevistadosInvestigadorPorPagina($conn, $pagina)
    {
        try {

            $sql = "SELECT
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
                (SELECT COUNT(*) FROM entrevista WHERE id_entrevistado = eo.id AND visible=1) AS n_entrevistas
            FROM
                entrevistado eo
            WHERE 
                eo.id_investigador =:id_investigador
            AND
                eo.visible = 1
            ORDER BY eo.create_time DESC
            LIMIT :limite OFFSET :offset";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':id_investigador', $this->idInvestigador);
            $stmt->bindValue(':limite', $this->limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', ($pagina - 1) * $this->limite, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Fail search lista entrevistados de investigador: " . $e->getMessage());
            return false;
        }
    }

    //Funcion encargada de contar todos los entrevistados
    public function contarTodos($conn)
    {
        try {

            $sql = "SELECT COUNT(*) FROM entrevistado AS n_entrevistados WHERE visible=1";

            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Fail conteo entrevistados totales: " . $e->getMessage());
            return false;
        }
    }

    //Funcion encargada contar todos los entrevistados de un investigador especifico
    public function contarEntrevistadosDeInvestigador($conn)
    {
        try {

            $sql = "SELECT COUNT(*) FROM entrevistado AS n_entrevistados WHERE id_investigador=? AND visible=1";
            $stmt = $conn->prepare($sql);

            $stmt->execute(array(
                $this->idInvestigador
            ));

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Fail conteo entrevistados totales del investigador: " . $e->getMessage());
            return false;
        }
    }

    public function entrevistadosPorGenero($conn, $proyecto, $idEmoticon, $letraGenero)
    {

        try {

            $sql = "SELECT 
                DISTINCT(e.id),
                        e.nombre,
                        e.apellido,
                        e.sexo,
                        COUNT(ev.id) as n_eventos
                FROM investigador i
                INNER JOIN
                    entrevistado e
                ON i.id = e.id_investigador
                INNER JOIN
                    entrevista n
                ON e.id = n.id_entrevistado
                INNER JOIN
                    evento ev
                ON n.id = ev.id_entrevista
                WHERE e.visible = 1
                    AND n.visible = 1
                    AND ev.visible = 1";


            if ($proyecto !== null) {
                $sql .= " AND i.proyecto= :proyecto";
            }

            if ($idEmoticon !== null) {
                $sql .= " AND ev.id_emoticon= :emoticon";
            }
            if ($letraGenero !== null) {
                $sql .= " AND e.sexo LIKE :letraGenero";
                $letraGenero .= "%";
            }

            $sql .= " GROUP BY e.id,e.nombre ORDER BY e.nombre";
            $stmt = $conn->prepare($sql);


            if ($proyecto !== null) {
                $stmt->bindParam(':proyecto', $proyecto, PDO::PARAM_STR);
            }

            if ($idEmoticon !== null) {
                $stmt->bindParam(':emoticon', $idEmoticon, PDO::PARAM_INT);
            }

            if ($letraGenero !== null) {
                $stmt->bindParam(':letraGenero', $letraGenero, PDO::PARAM_STR);
            }

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Fail search entrevistas por genero: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($conn): bool
    {

        try {
            //PYSHICAL DELETE
            //$stmt = $conn->prepare(
            //    "DELETE FROM entrevistado WHERE id=?"
            //);

            //LOGICAL DELETE

            $sql = "UPDATE entrevistado SET visible = 0 WHERE id=?";

            $stmt = $conn->prepare($sql);

            $stmt->execute(array($this->id));

            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            error_log("Fail delete entrevistado: " . $e->getMessage());
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

    public function setSexo($sexo): void
    {
        $this->sexo = $sexo;
    }

    public function setFechaNac($fechaNac): void
    {
        $this->fechaNac = $fechaNac;
    }

    public function setNombreCiudad($nombreCiudad): void
    {
        $this->nombreCiudad = $nombreCiudad;
    }

    public function setJubiladoLegal($jubiladoLegal): void
    {
        $this->jubiladoLegal = $jubiladoLegal;
    }

    public function setCaidas($caidas): void
    {
        $this->caidas = $caidas;
    }

    public function setNCaidas($nCaidas): void
    {
        $this->nCaidas = $nCaidas;
    }

    public function setNConvivientes($n_convivientes_3_meses): void
    {
        $this->nConvivientes3meses = $n_convivientes_3_meses;
    }

    public function setIdInvestigador($idInvestigador): void
    {
        $this->idInvestigador = $idInvestigador;
    }


    public function setIdNivelEducacional($idNivelEducacional): void
    {
        $this->idNivelEducacional = $idNivelEducacional;
    }

    public function setIdEstadoCivil($idEstadoCivil): void
    {
        $this->idEstadoCivil = $idEstadoCivil;
    }

    public function setIdTipoConvivencia($idTipoConvivencia): void
    {
        $this->idTipoConvivencia = $idTipoConvivencia;
    }

    public function setNombreProfesion($nombreProfesion): void
    {
        $this->nombreProfesion = $nombreProfesion;
    }
}
