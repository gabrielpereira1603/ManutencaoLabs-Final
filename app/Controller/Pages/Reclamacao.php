<?php

namespace app\Controller\Pages;

use \app\Model\Entity\Computador as EntityComputador; 
use \app\Model\Entity\Componente as EntityComponente; 
use \app\Model\Entity\Reclamacao as EntityReclamacao; 
use \app\Session\User\Login as SessionUserLogin;
use \app\Utils\View;
use \app\Http\Router;

class Reclamacao extends Page {

    public static function getComponenteItens($request){
        // componentes
        $itens = '';

        $results = EntityComponente::getComponentes();

        while ($obComponente = $results->fetchObject(EntityComponente::class)) {
            $itens .= View::render('Pages/componente/item', [
                'nome_componente'=> $obComponente->nome_componente,
                'codcomponente' => $obComponente->codcomponente,    
            ]);
        }
        //RETORNA OS DEPOIMENTOS
        return $itens;
    }

    public static function getReclamacaoAbertaItens($request) {
        $itens = '';
        $userData = $_SESSION['user']['usuario'];
        $codusuario = $userData['codusuario'];
        
        $results = EntityReclamacao::getReclamacaoAbertas($codusuario);

        while ($obReclamacao = $results->fetchObject(EntityReclamacao::class)) {
            $itens .= View::render('Pages/reclamacao/item', [
                'codreclamacao' => $obReclamacao->codreclamacao,
                'status' => $obReclamacao->status,
                'descricao'=> $obReclamacao->descricao,
                'datahota_reclamacao' => $obReclamacao->datahora_reclamacao,
                'datahota_fimreclamacao' => isset($obReclamacao->datahora_fimreclamacao) ?  $obReclamacao->datahora_fimreclamacao : 'Nenhum Horario Encontrado!' ,
                'login' => $obReclamacao->login,
                'nome_usuario' => $obReclamacao->nome_usuario,
                'email_usuario' => $obReclamacao->email_usuario,
                'numerolaboratorio' => $obReclamacao->numerolaboratorio,
                'patrimonio' => $obReclamacao->patrimonio,
                'codcomputador' => $obReclamacao->codcomputador,
                'nome_componente' => $obReclamacao->componentes,
            ]);
        }
        return $itens;
    }
  
    public static function getReclamacao($request,$codcomputador) {

        $obComponente = EntityComponente::getComponentes();
        $obComputador = EntityComputador::getInfoComputador($codcomputador);

        $content = View::render('Pages/modules/insererirReclamacao/index', [
            'itens' => self::getComponenteItens($request),
            'nav' => parent::getNav($request),
            'codcomputador' => $codcomputador,
            'codlaboratorio'=> $obComputador->codlaboratorio,
            'numerolaboratorio' => $obComputador->numerolaboratorio,
            'patrimonio' => $obComputador->patrimonio,
        ]);
        
        //RETORNA A PAGINA COMPLETA
        return parent::getPage('Reclamação',$content);
    }

    public static function setUpdateReclamacao($request) {
        $postVars = $request->getPostVars();
        $codreclamacao = $postVars['codreclamacao'] ?? '';
        $descricao = $postVars['editarDescricao'] ?? '';
        $componente = $postVars['componentesSelecionados'] ?? [];
        
        if($result = EntityReclamacao::UpdateReclamacao($codreclamacao,$descricao,$componente)){
            $request->getRouter()->redirect('/reclamacoesAbertas?success=reclamacaoUpdate');
        }else {
            $request->getRouter()->redirect('/reclamacoesAbertas?error=reclamacaoUpdateNot');
        }
    }

    public static function setDeleteReclamacao($request) {
        $postVars = $request->getPostVars();
        $codreclamacao = $postVars['codreclamacao'] ?? '';
        $codcomputador = $postVars['codcomputador'] ?? '';

        if($result = EntityReclamacao::deleteReclamacao($codreclamacao,$codcomputador)){
            $request->getRouter()->redirect('/reclamacoesAbertas?success=delete');
        }else {
            $request->getRouter()->redirect('/reclamacoesAbertas?error=deleteError');
        }
    }

    /**
     * Metodo responsavel por mostrar as reclamacaoes feitas pelo o usuario 
    */
    public static function getReclamacaoAbertas($request) {
        
        $content = View::render('Pages/modules/reclamacoesAbertas/index', [
            'itens' => self::getReclamacaoAbertaItens($request),
        ]);
        
        //RETORNA A PAGINA COMPLETA
        return parent::getPanel('Reclamações',$content,'reclamacoesAbertas');
    }
    /**
     * Metodo responsavel por definir o login do usuario
     * @param Request
     */
    public static function setReclamacao($request){
        // Obtém os dados do usuário logado
        $userData = $_SESSION['user']['usuario'];
        $codUsuario = $userData['codusuario'];
    
        // Obtém as informações da requisição
        $postVars = $request->getPostVars();
        $descricao = $postVars['descricao'] ?? '';
        $componente = $postVars['componente'] ?? [];
        $codlaboratorio = $postVars['codlaboratorio'] ?? '';
        $codcomputador = $postVars['codcomputador'] ?? '';
    
        // Dentro do seu método setReclamacao na controller
        if (!empty($_FILES['foto-reclamacao']['tmp_name'][0])) {
            $numFiles = count($_FILES['foto-reclamacao']['tmp_name']);

            // Itera sobre cada foto e armazena no array $foto
            $foto = [];
            for ($i = 0; $i < $numFiles; $i++) {
                $foto_tmp_name = $_FILES['foto-reclamacao']['tmp_name'][$i];
                $foto_content = file_get_contents($foto_tmp_name); // Lê o conteúdo binário da foto
                $foto[] = $foto_content;
            }
        } else {
            $foto = [];
        }
        
        // Cria uma nova instância da entidade Reclamacao
        $obReclamacao = new EntityReclamacao();
    
        // Define os atributos da reclamação
        $obReclamacao->descricao = $descricao;
        $obReclamacao->codusuario_fk = $codUsuario;
        $obReclamacao->codlaboratorio_fk = $codlaboratorio;
        $obReclamacao->codcomputador_fk = $codcomputador;
    
        // Chama o método para cadastrar a reclamação
        $success = $obReclamacao->cadastrarReclamacao($componente,$foto);
    
        if ($success) {
            $request->getRouter()->redirect('/?success=reclamacaoAdd');
        } else {
            $request->getRouter()->redirect('/?error=reclamacaoNot');
        }
    }
    
}