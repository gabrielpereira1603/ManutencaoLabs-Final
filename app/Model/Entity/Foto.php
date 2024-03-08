<?php

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class Foto {
    public $codfoto;

    public $foto_reclamacao;
     
    public static function setFoto($foto) {
        foreach ($foto as $fotos) {
            $database = new Database('foto');
            $codFoto = $database->insert([
                'foto_reclamacao' => $fotos
            ],);
        }
        return $codFoto;
    }
}