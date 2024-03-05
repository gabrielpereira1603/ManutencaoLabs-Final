<?php 

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;


class Laboratorio {

    /**
     * Cod Laboratório
     * @param int
     */
    public $codlaboratorio;

    /**
     * Nome Laboratório
     * @param string
     */
    public $numerolaboratorio;

    public static function getNumeroLaboratorio($codlaboratorio) {
        // Condição para selecionar os numeroLab pelo código do laboratório
        $where = "laboratorio.codlaboratorio = $codlaboratorio";
        return (new Database('laboratorio'))->select($where);
    }

    /**
     * Metodo responsavel por retornar depoimentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fiel
     * @return //PDOStatement
    */
    public static function getLaboratorios() {
        return (new Database('laboratorio'))->select($where = null, $order = null, $limit = null, $offset = null, $fields = '*', $join = null);
    }
}