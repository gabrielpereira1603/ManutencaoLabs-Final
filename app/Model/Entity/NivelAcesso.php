<?php

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class NivelAcesso {
    public $codsituacao;
    public $tiposituacao;

    public static function getNivelAcesso() {
        return (new Database('nivel_acesso'))->select()->fetchAll();
    }

}