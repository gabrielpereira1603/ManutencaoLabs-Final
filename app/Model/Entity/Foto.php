<?php

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class Foto {
    public $codfoto;

    public $foto;

    public $codreclamacao_fk;
     
    public static function setFoto($foto) {
        foreach ($foto as $fotos) {
            $database = new Database('foto');
            $codFoto = $database->insert([
                'foto_reclamacao' => $fotos
            ],);
        }
        return $codFoto;
    }

    public static function getFotoReclamacao($codreclamacao) {
        $where = "codreclamacao_fk = $codreclamacao";
    
        $fields = "foto.foto";
        return (new Database('foto'))->select($where, null, null,null, $fields, null)->fetchAll();
    }
}