<?php

class Estadisticas
{

    private $id;
    private $nombre_es;
    private $nombre_en;
    private $url;

    public function getId()
    {
        return $this->id;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getNombreEs()
    {
        return $this->nombre_es;
    }

    public function setNombreEs($nombre_es)
    {
        $this->nombre_es = $nombre_es;
    }

    public function getNombreEn()
    {
        return $this->nombre_en;
    }

    public function setNombreEn($nombre_en)
    {
        $this->nombre_en = $nombre_en;
    }

    public function buscarEstadisticas($conn)
    {

        try {

            $stmt = $conn->query(
                "SELECT * FROM estadisticas"
            );
            $listado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $listado;
        } catch (PDOException $e) {
            error_log("Fail search lista estadisticas: " . $e->getMessage(), 0);
            return false;
        }
    }
}
