<?php

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;
use DateTime; 
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
     * @var \DateTime
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
        // Obtenha a datahora atual como uma string no formato desejado
        
        // Converta a datahora para o formato desejado
        $datahoraFormatada = $this->datahora_manutencao = date("Y-m-d H:i:s");
        
        $database = new Database('manutencao');
        $this->codmanutencao = $database->insert([
            'descricao_manutencao' => $this->descricao_manutencao,
            'datahora_manutencao' => $this->datahora_manutencao,
            'codusuario_fk' => $this->codusuario_fk,
            'codreclamacao_fk' => $this->codreclamacao_fk
        ]);

        $computadorDatabase = new Database('computador');
        $where = $codcomputador;
        $value = 2;
        $computadorDatabase->updateComputerSituation($where, $value);

        $reclamacaoDatabase = new Database('reclamacao');
        $where = $this->codreclamacao_fk;
        $values = 'Concluída';
        $reclamacaoDatabase->updateStatusReclamacao($where, $values);

        $reclamacaoDatabase->updateFimreclamacao($this->codreclamacao_fk,$this->datahora_manutencao);

        // Verifica se a reclamação foi fechada em atraso
        $reclamacao = Reclamacao::findById($this->codreclamacao_fk);
        $datahora_reclamacao = strtotime($reclamacao[0]['datahora_reclamacao']);
        $datahora_fimreclamacao = strtotime($datahoraFormatada);
        $diferenca_dias = ($datahora_fimreclamacao - $datahora_reclamacao) / (60 * 60 * 24); // Calcula a diferença em dias

        if ($diferenca_dias > 1) {
            // Se a diferença for maior que 1 dia, atualiza o status para "Fechada em atraso"
            $reclamacaoDatabase->updateStatusReclamacao($where, 'Fechada em atraso');
        }
    }
}