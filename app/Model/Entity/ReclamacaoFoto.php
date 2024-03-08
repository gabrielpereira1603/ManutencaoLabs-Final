<?php

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class ReclamacaoFoto {
    public $codreclamacao_fk;
    public $codfoto_fk;

    public static function setReclamacaoFoto($foto,$codreclamacao) {
        foreach ($foto as $fotos) {
            $database = new Database('reclamacao_foto');
            $database->insert([
                'codreclamacao_fk' => $codreclamacao, 
                'codfoto_fk' => $fotos
            ],);
        }
        return true;
    }
}
