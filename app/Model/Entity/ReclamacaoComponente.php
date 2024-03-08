<?php

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class ReclamacaoComponente {
    public $codreclamacao_fk;
    public $codcomponente_fk;

    public static function setReclamacaoComponente($componente,$codreclamacao) {
        foreach ($componente as $codComponente) {
            $database = new Database('reclamacao_componente');
            $database->insert([
                'codreclamacao_fk' => $codreclamacao, 
                'codcomponente_fk' => $codComponente
            ],);
        }
        return true;
    }
}
