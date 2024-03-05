<?php

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class Componente {

    /**
     * Cod Componente
     * @var int
     */
    public $codcomponente;

    /**
     * Nome Componente
     * @var string
     */
    public $nome_componente;


    public static function getComponentes(){
        // Chama o método select da classe Database, passando a cláusula de junção e a cláusula ORDER BY
        return (new Database('componente'))->select();
    }

}