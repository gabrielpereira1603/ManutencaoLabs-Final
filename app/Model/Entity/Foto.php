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

    public static function getFotoReclamacao($codreclamacao) {
        $where = "codreclamacao_fk = $codreclamacao";
    
        $join = "INNER JOIN foto ON reclamacao_foto.codfoto_fk = foto.codfoto";
    
        $fields = "foto.foto_reclamacao";
        return (new Database('reclamacao_foto'))->select($where, null, null,null, $fields, $join)->fetchAll();
    }
}