<?php

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class Reclamacao {

    /**
     * Cod Reclamacao
     * @var int
     */
    public $codreclamacao;
    public $descricao;
    public $status;
    public $datahora_reclamacao;
    public $foto_reclamacao;
    public $codcomputador_fk;
    public $codlaboratorio_fk;
    public $codusuario_fk;

    /**
     * Metodo responsavel por trazer as reclamacoes realizadas pelo o usuario que estao em abertas
     */
    public static function getReclamacaoAbertas($codusuario){
        $where = "reclamacao.codusuario_fk = $codusuario
        AND reclamacao.status = 'Em aberto' GROUP BY reclamacao.codreclamacao";

        $join = ' INNER JOIN 
            usuario ON reclamacao.codusuario_fk = usuario.codusuario 
        INNER JOIN 
            laboratorio ON reclamacao.codlaboratorio_fk = laboratorio.codlaboratorio 
        INNER JOIN 
            computador ON reclamacao.codcomputador_fk = computador.codcomputador 
        INNER JOIN 
            reclamacao_componente ON reclamacao.codreclamacao = reclamacao_componente.codreclamacao_fk 
        INNER JOIN 
            componente ON componente.codcomponente = reclamacao_componente.codcomponente_fk';
        
        // Ajuste aqui para usar GROUP_CONCAT
        $fields = 'reclamacao.*, usuario.login, usuario.nome_usuario, usuario.email_usuario, 
                    laboratorio.numerolaboratorio, computador.patrimonio, 
                    GROUP_CONCAT(componente.nome_componente SEPARATOR \', \') AS componentes';
        
        return (new Database('reclamacao'))->select($where, null, null,null, $fields, $join);
    }

    /**
     * Metodo que tras total de reclamacao por laboratorio
     */
    public static function reclamacaoPorLab() {
        // Monta a string para os campos que queremos selecionar
        $fields = 'laboratorio.codlaboratorio, laboratorio.numerolaboratorio, COUNT(reclamacao.codreclamacao) AS total_reclamacoes';
    
        // Monta a string para os joins das tabelas
        $join = 'LEFT JOIN computador ON laboratorio.codlaboratorio = computador.codlaboratorio_fk
                 LEFT JOIN reclamacao ON computador.codcomputador = reclamacao.codcomputador_fk';
    
        // Agrupa os resultados pelo número do laboratório
        $groupBy = 'laboratorio.codlaboratorio, laboratorio.numerolaboratorio';
    
        // Chama o método select da classe Database com os parâmetros construídos
        return (new Database('laboratorio'))->select(null, 'total_reclamacoes DESC', null, null, $fields, $join . ' GROUP BY ' . $groupBy);
    }
    
    
    public static function getComponenteReclamacao($codcomputador) {
        $where = "reclamacao.codcomputador_fk = $codcomputador AND reclamacao.status = 'Em aberto'";

        $join =  ' INNER JOIN usuario ON reclamacao.codusuario_fk = usuario.codusuario
          INNER JOIN laboratorio ON reclamacao.codlaboratorio_fk = laboratorio.codlaboratorio
          INNER JOIN computador ON reclamacao.codcomputador_fk = computador.codcomputador
          INNER JOIN reclamacao_componente ON reclamacao.codreclamacao = reclamacao_componente.codreclamacao_fk
          INNER JOIN componente ON componente.codcomponente = reclamacao_componente.codcomponente_fk';
        
        
        // Defina apenas os campos necessários na consulta
        $fields = 'reclamacao.*, usuario.login, usuario.nome_usuario, usuario.email_usuario, 
                   laboratorio.numerolaboratorio, computador.patrimonio, componente.nome_componente';
        
        return (new Database('reclamacao'))->select($where, null, null,null, $fields, $join)->fetchAll();
    }

    public static function getDetailsReclamacao($codcomputador) {
        $where = "reclamacao.codcomputador_fk = $codcomputador AND reclamacao.status = 'Em aberto'";

        $join = 
        'INNER JOIN usuario ON reclamacao.codusuario_fk = usuario.codusuario
        INNER JOIN laboratorio ON reclamacao.codlaboratorio_fk = laboratorio.codlaboratorio
        INNER JOIN computador ON reclamacao.codcomputador_fk = computador.codcomputador';
        
        // Defina apenas os campos necessários na consulta
        $fields = 'reclamacao.*, usuario.login, usuario.nome_usuario, usuario.email_usuario, 
                   laboratorio.numerolaboratorio, computador.patrimonio';
        
        return (new Database('reclamacao'))->select($where, null, null,null, $fields, $join)->fetchAll();
    }

    /**
     * Metodo responsavel por cadastra a instancia atual do banco de dados
     * @return boolean
    */
    public function cadastrarReclamacao($componente, $foto) {
        // Define a data atual
        $this->datahora_reclamacao = date('Y-m-d H:i:s');
    
        // Insere a reclamação no banco de dados
        $database = new Database('reclamacao');
        $this->codreclamacao = $database->insert([
            'descricao' => $this->descricao,
            'status' => 'Em aberto',
            'datahora_reclamacao' => $this->datahora_reclamacao,
            'codcomputador_fk' => $this->codcomputador_fk,
            'codlaboratorio_fk' => $this->codlaboratorio_fk,
            'codusuario_fk' => $this->codusuario_fk,
        ]);
    
        // INSERE AS FOTOS NO BANCO DE DADOS
        $databaseFoto = new Database('foto');
        foreach ($foto as $fotos) {
            // Salvar a foto no banco de dados
            $codFoto = $databaseFoto->insert([
                'foto_reclamacao' => $fotos
            ]);
            // Vincular a foto à reclamação na tabela reclamacao_foto
            $databaseReclamacaoFoto = new Database('reclamacao_foto');
            $databaseReclamacaoFoto->insert([
                'codreclamacao_fk' => $this->codreclamacao,
                'codfoto_fk' => $codFoto
            ]);
        }
    
        // INSERE OS COMPONENTES NA TABELA RECLAMACAO_COMPONENTE
        $reclamacaoComponente = new ReclamacaoComponente(); // Instanciando a classe ReclamacaoComponente
        $reclamacaoComponente->setReclamacaoComponente($componente, $this->codreclamacao); // Chamando o método para inserir os componentes
    
        // Atualiza o tipo de situação do computador para 3
        $computadorDatabase = new Database('computador');
        $where = $this->codcomputador_fk;
        $values = 1;
        $computadorDatabase->updateComputerSituation($where, $values);
    
        return true;
    }    

    public static function findById($codreclamacao) {
        $where = "reclamacao.codreclamacao = $codreclamacao"; // Condição para o código da reclamação
    
        $join = 'INNER JOIN usuario ON reclamacao.codusuario_fk = usuario.codusuario
                 INNER JOIN laboratorio ON reclamacao.codlaboratorio_fk = laboratorio.codlaboratorio
                 INNER JOIN computador ON reclamacao.codcomputador_fk = computador.codcomputador';
    
        // Defina apenas os campos necessários na consulta
        $fields = 'reclamacao.*, usuario.login, usuario.nome_usuario, usuario.email_usuario, 
                   laboratorio.numerolaboratorio, computador.patrimonio';
    
        return (new Database('reclamacao'))->select($where, null, null, null, $fields, $join)->fetchAll();
    }


}
