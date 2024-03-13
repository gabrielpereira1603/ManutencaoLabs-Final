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

    public static function ComponenteReclamacao($codReclamacao) {
        // Condição para selecionar os componentes pela reclamação
        $where = "reclamacao.codreclamacao = $codReclamacao";
    
        // Cláusula de junção para unir a tabela componente com a tabela reclamacao_componente e reclamacao
        $join = 'INNER JOIN reclamacao_componente ON componente.codcomponente = reclamacao_componente.codcomponente_fk
                 INNER JOIN reclamacao ON reclamacao_componente.codreclamacao_fk = reclamacao.codreclamacao';
    
        // Chama o método select da classe Database, passando a cláusula de junção
        return (new Database('componente'))->select($where, null, null, null, 'componente.*', $join);
    }

}