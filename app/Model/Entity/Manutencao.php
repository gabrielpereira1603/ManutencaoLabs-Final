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
        $value = 1;
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

        $reclamacao = new Reclamacao();
        $prazoReclamacaoObject = $reclamacao->getPrazoReclamacao($this->codreclamacao_fk);
        $prazoReclamacaoArray = (array) $prazoReclamacaoObject; // Convert object to array
        $prazoReclamacao = $prazoReclamacaoArray['prazo_reclamacao'];
        
        
        if ($diferenca_dias > 1) {
            // Se a diferença for maior que 1 dia, atualiza o status para "Fechada em atraso"
            $reclamacaoDatabase->updateStatusReclamacao($where, 'Fechada em atraso');
        }
    }

    public static function ManutencaoPorUser() {
        // Monta a string para os campos que queremos selecionar
        $fields = 'usuario.nome_usuario, COUNT(manutencao.codmanutencao) AS total_manutencoes';
        
        // Monta a string para os joins das tabelas
        $join = 'INNER JOIN manutencao ON usuario.codusuario = manutencao.codusuario_fk';
        
        // Agrupa os resultados pelo código do usuário e nome do usuário
        $groupBy = 'usuario.codusuario, usuario.nome_usuario';
        
        // Chama o método select da classe Database com os parâmetros construídos
        return (new Database('usuario'))->select(null, 'total_manutencoes DESC', null, null, $fields, $join . ' GROUP BY ' . $groupBy);
    }
    
    public static function gerarRelatorioManutencao($usuario,$laboratorio,$computador,$dataInicio,$dataFim) {
        // var_dump('lab:'.$laboratorio,'pc:'.$computador,'user:'.$usuario);
        if($laboratorio == -1 && $computador == -1 && $usuario == -1) {
            $fields = ' 
            manutencao.datahora_manutencao, 
            manutencao.descricao_manutencao, 
            usuario_manutencao.nome_usuario AS nome_usuario_manutencao, 
            usuario_manutencao.login AS login_manutencao, 
            usuario_manutencao.nivelacesso_fk AS nivelacesso_fk_manutencao, 
            reclamacao.status AS status_reclamacao, 
            computador.patrimonio, 
            laboratorio.numerolaboratorio, 
            reclamacao.descricao AS descricao_reclamacao, 
            reclamacao.datahora_reclamacao, 
            GROUP_CONCAT(componente.nome_componente) AS componentes,
            usuario_reclamacao.nome_usuario AS nome_usuario_reclamacao, 
            usuario_reclamacao.login AS login_reclamacao, 
            usuario_reclamacao.nivelacesso_fk AS nivelacesso_fk_reclamacao';

            $join = '
             INNER JOIN usuario AS usuario_manutencao ON manutencao.codusuario_fk = usuario_manutencao.codusuario 
            LEFT JOIN reclamacao ON manutencao.codreclamacao_fk = reclamacao.codreclamacao 
            LEFT JOIN computador ON reclamacao.codcomputador_fk = computador.codcomputador 
            LEFT JOIN laboratorio ON reclamacao.codlaboratorio_fk = laboratorio.codlaboratorio 
            LEFT JOIN reclamacao_componente ON reclamacao.codreclamacao = reclamacao_componente.codreclamacao_fk 
            LEFT JOIN componente ON reclamacao_componente.codcomponente_fk = componente.codcomponente 
            LEFT JOIN usuario AS usuario_reclamacao ON reclamacao.codusuario_fk = usuario_reclamacao.codusuario';
            
            $where = "(manutencao.datahora_manutencao BETWEEN '$dataInicio' AND '$dataFim') GROUP BY manutencao.codmanutencao";
        }else if($laboratorio == -1 && $computador == -1) {
            $fields = '                
            manutencao.datahora_manutencao, 
            manutencao.descricao_manutencao, 
            usuario_manutencao.nome_usuario AS nome_usuario_manutencao, 
            usuario_manutencao.login AS login_manutencao, 
            usuario_manutencao.nivelacesso_fk AS nivelacesso_fk_manutencao, 
            reclamacao.status AS status_reclamacao, 
            computador.patrimonio, 
            laboratorio.numerolaboratorio, 
            reclamacao.descricao AS descricao_reclamacao, 
            reclamacao.datahora_reclamacao, 
            GROUP_CONCAT(componente.nome_componente) AS componentes,
            usuario_reclamacao.nome_usuario AS nome_usuario_reclamacao, 
            usuario_reclamacao.login AS login_reclamacao, 
            usuario_reclamacao.nivelacesso_fk AS nivelacesso_fk_reclamacao';

            $where = "(manutencao.datahora_manutencao BETWEEN '$dataInicio' AND '$dataFim') 
            AND manutencao.codusuario_fk = $usuario 
            GROUP BY manutencao.codmanutencao";

            $join = '
            INNER JOIN usuario AS usuario_manutencao ON manutencao.codusuario_fk = usuario_manutencao.codusuario 
            LEFT JOIN reclamacao ON manutencao.codreclamacao_fk = reclamacao.codreclamacao 
            LEFT JOIN computador ON reclamacao.codcomputador_fk = computador.codcomputador 
            LEFT JOIN laboratorio ON reclamacao.codlaboratorio_fk = laboratorio.codlaboratorio 
            LEFT JOIN reclamacao_componente ON reclamacao.codreclamacao = reclamacao_componente.codreclamacao_fk 
            LEFT JOIN componente ON reclamacao_componente.codcomponente_fk = componente.codcomponente 
            LEFT JOIN usuario AS usuario_reclamacao ON reclamacao.codusuario_fk = usuario_reclamacao.codusuario';
        } else if($usuario == -1 && $computador == -2) {
            $fields = '                
            manutencao.datahora_manutencao, 
            manutencao.descricao_manutencao, 
            usuario_manutencao.nome_usuario AS nome_usuario_manutencao, 
            usuario_manutencao.login AS login_manutencao, 
            usuario_manutencao.nivelacesso_fk AS nivelacesso_fk_manutencao, 
            reclamacao.status AS status_reclamacao, 
            computador.patrimonio, 
            laboratorio.numerolaboratorio, 
            reclamacao.descricao AS descricao_reclamacao, 
            reclamacao.datahora_reclamacao, 
            GROUP_CONCAT(componente.nome_componente) AS componentes,
            usuario_reclamacao.nome_usuario AS nome_usuario_reclamacao, 
            usuario_reclamacao.login AS login_reclamacao, 
            usuario_reclamacao.nivelacesso_fk AS nivelacesso_fk_reclamacao';

            $where = "(manutencao.datahora_manutencao BETWEEN '$dataInicio' AND '$dataFim') 
            AND laboratorio.codlaboratorio = $laboratorio 
            GROUP BY manutencao.codmanutencao";

            $join = '
            INNER JOIN usuario AS usuario_manutencao ON manutencao.codusuario_fk = usuario_manutencao.codusuario 
            LEFT JOIN reclamacao ON manutencao.codreclamacao_fk = reclamacao.codreclamacao 
            LEFT JOIN computador ON reclamacao.codcomputador_fk = computador.codcomputador 
            LEFT JOIN laboratorio ON reclamacao.codlaboratorio_fk = laboratorio.codlaboratorio 
            LEFT JOIN reclamacao_componente ON reclamacao.codreclamacao = reclamacao_componente.codreclamacao_fk 
            LEFT JOIN componente ON reclamacao_componente.codcomponente_fk = componente.codcomponente 
            LEFT JOIN usuario AS usuario_reclamacao ON reclamacao.codusuario_fk = usuario_reclamacao.codusuario';
        } else if ($computador == -2){
            $fields = '                
            manutencao.datahora_manutencao, 
            manutencao.descricao_manutencao, 
            usuario_manutencao.nome_usuario AS nome_usuario_manutencao, 
            usuario_manutencao.login AS login_manutencao, 
            usuario_manutencao.nivelacesso_fk AS nivelacesso_fk_manutencao, 
            reclamacao.status AS status_reclamacao, 
            computador.patrimonio, 
            laboratorio.numerolaboratorio, 
            reclamacao.descricao AS descricao_reclamacao, 
            reclamacao.datahora_reclamacao, 
            GROUP_CONCAT(componente.nome_componente) AS componentes,
            usuario_reclamacao.nome_usuario AS nome_usuario_reclamacao, 
            usuario_reclamacao.login AS login_reclamacao, 
            usuario_reclamacao.nivelacesso_fk AS nivelacesso_fk_reclamacao';

            $where = "(manutencao.datahora_manutencao BETWEEN '$dataInicio' AND '$dataFim')
            AND manutencao.codusuario_fk = $usuario
            AND laboratorio.codlaboratorio = $laboratorio
            GROUP BY manutencao.codmanutencao";

            $join = '
            INNER JOIN usuario AS usuario_manutencao ON manutencao.codusuario_fk = usuario_manutencao.codusuario 
            LEFT JOIN reclamacao ON manutencao.codreclamacao_fk = reclamacao.codreclamacao 
            LEFT JOIN computador ON reclamacao.codcomputador_fk = computador.codcomputador 
            LEFT JOIN laboratorio ON reclamacao.codlaboratorio_fk = laboratorio.codlaboratorio 
            LEFT JOIN reclamacao_componente ON reclamacao.codreclamacao = reclamacao_componente.codreclamacao_fk 
            LEFT JOIN componente ON reclamacao_componente.codcomponente_fk = componente.codcomponente 
            LEFT JOIN usuario AS usuario_reclamacao ON reclamacao.codusuario_fk = usuario_reclamacao.codusuario';
        
        } else {
            $fields = '                
            manutencao.datahora_manutencao, 
            manutencao.descricao_manutencao, 
            usuario_manutencao.nome_usuario AS nome_usuario_manutencao, 
            usuario_manutencao.login AS login_manutencao, 
            usuario_manutencao.nivelacesso_fk AS nivelacesso_fk_manutencao, 
            reclamacao.status AS status_reclamacao, 
            computador.patrimonio, 
            laboratorio.numerolaboratorio, 
            reclamacao.descricao AS descricao_reclamacao, 
            reclamacao.datahora_reclamacao, 
            GROUP_CONCAT(componente.nome_componente) AS componentes,
            usuario_reclamacao.nome_usuario AS nome_usuario_reclamacao, 
            usuario_reclamacao.login AS login_reclamacao, 
            usuario_reclamacao.nivelacesso_fk AS nivelacesso_fk_reclamacao';

            $where = "
             (manutencao.datahora_manutencao BETWEEN '$dataInicio' AND '$dataFim')
            AND manutencao.codusuario_fk = $usuario
            AND laboratorio.codlaboratorio = $laboratorio
            AND computador.codcomputador = $computador";

            $join = '
            INNER JOIN usuario AS usuario_manutencao ON manutencao.codusuario_fk = usuario_manutencao.codusuario 
            LEFT JOIN reclamacao ON manutencao.codreclamacao_fk = reclamacao.codreclamacao 
            LEFT JOIN computador ON reclamacao.codcomputador_fk = computador.codcomputador 
            LEFT JOIN laboratorio ON reclamacao.codlaboratorio_fk = laboratorio.codlaboratorio 
            LEFT JOIN reclamacao_componente ON reclamacao.codreclamacao = reclamacao_componente.codreclamacao_fk 
            LEFT JOIN componente ON reclamacao_componente.codcomponente_fk = componente.codcomponente 
            LEFT JOIN usuario AS usuario_reclamacao ON reclamacao.codusuario_fk = usuario_reclamacao.codusuario';
        }
        return (new Database('manutencao'))->select($where, null, null,null, $fields, $join)->fetchAll();
    }
}