<?php

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class Computador {

    /**
     * Cod Computador
     * @var int
     */
    public $codcomputador;

    /**
     * Patrimonio
     * @var string
     */
    public $patrimonio;

    /**
     * Codsitauacao_fk
     * @var int
     */
    public $codsituacao_fk;

    /**
     * Codsituacao
     *  @var int
     */
    public $codsituacao;

    /**
     * Tipo Situacao
     * @var string
     */
    public $tiposituacao;

    /**
     * Codlaboratorio_fk
     * @var int
    */
    public $codlaboratorio_fk;

    /**
     * Codlaboratorio
     * @var int
    */
    public $codlaboratorio;

    /**
     * Numero laboratorio
     * @var string
     */
    public $numerolaboratorio;

    /**
     * Metodo responsavel por retornar quantidade de computadores de cada lab
     * @param int $codlaboratorio
     * @return //PDOStatement
    */
    public static function getQuantidadeComputadores($codlaboratorio){
        // Conexão com o banco de dados
        $db = new Database('computador');
    
        // Condição para selecionar os computadores pelo código do laboratório
        $where = "codlaboratorio_fk = $codlaboratorio";
    
        // Realiza a consulta para contar o número de registros
        $result = $db->select($where, null, null, 'COUNT(*) as total')->fetchObject();
    
        // Retorna a quantidade total de computadores para o laboratório especificado
        return $result->total;
    }

    /**
     * Método responsável por retornar os computadores de um laboratório com paginação
     * @param int $codLaboratorio
     * @param Pagination $obPagination
     * @param int $limit
     * @return //PDOStatement
     */
    public static function getComputadoresLaboratorioPagination($codLaboratorio, $obPagination, $limit) {
        // Condição para selecionar os computadores pelo código do laboratório
        $where = "computador.codlaboratorio_fk = $codLaboratorio";

        // Cláusula de junção para unir a tabela computador com a tabela situacao
        $join = 
        'INNER JOIN situacao ON computador.codsituacao_fk = situacao.codsituacao
        INNER JOIN laboratorio ON computador.codlaboratorio_fk = laboratorio.codlaboratorio';

        // Cláusula ORDER BY para ordenar os resultados pelo campo 'patrimonio' em ordem crescente
        $orderBy = 'patrimonio ASC';

        // Chama o método select da classe Database, passando a cláusula de junção, a cláusula ORDER BY e o limite
        return (new Database('computador'))->select($where, $orderBy, $limit, '*', $join);
    }

        /**
     * Metodo responsavel por retornar depoimentos
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fiel
     * @return //PDOStatement
    */
    public static function getComputadoresLaboratorio($codLaboratorio) {
        // Condição para selecionar os computadores pelo código do laboratório
        $where = "computador.codlaboratorio_fk = $codLaboratorio";
    
        // Cláusula de junção para unir a tabela computador com a tabela situacao
        $join = 'INNER JOIN situacao ON computador.codsituacao_fk = situacao.codsituacao';
    
        // Cláusula ORDER BY para ordenar os resultados pelo campo 'patrimonio' em ordem crescente
        $orderBy = 'patrimonio ASC';
    
        // Chama o método select da classe Database, passando a cláusula de junção e a cláusula ORDER BY
        return (new Database('computador'))->select($where, $orderBy, null, '*', $join);
    }

}