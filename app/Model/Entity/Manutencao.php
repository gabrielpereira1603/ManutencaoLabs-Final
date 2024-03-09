<?php

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class Manutencao {
    /**
     * $id Manutencao
     * @var int
     */
    public $codmanutencao;

    /**
     * Descricao da manutencao
     * @var string
     */
    public $descricao_manutencao;

    /**
     * datahora_manutencao
     * @var datetime
     */
    public $datahora_manutencao;

    /**
     * Chave estrangeira de usuario
     * @var int
     */
    public $codusuario_fk;

    /**
     * Codreclamacao chave estrangeira
     * @var int
     */
    public $codreclamacao_fk;


    public function cadastrarManutencao($codcomputador) {
        $this->datahora_manutencao = date("Y-m-d H:i:s");
        
        $database = new Database('manutencao');
        $this->codmanutencao = $database->insert([
            'descricao_manutencao' => $this->descricao_manutencao,
            'datahora_manutencao' => $this->datahora_manutencao,
            'codusuario_fk' => $this->codusuario_fk,
            'codreclamacao_fk' => $this->codreclamacao_fk
        ]);

        $computadorDatabase = new Database('computador');
        $where = $codcomputador;
        $value = 1;
        $computadorDatabase->updateComputerSituation($where, $value);

        $reclamacaoDatabase = new Database('reclamacao');
        $where = $this->codreclamacao_fk;
        $values = 'Concluída';
        $reclamacaoDatabase->updateStatusReclamacao($where, $values);
    }
}