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
            // 'paginaAtual' => $paginaAtual,
        ]);
        
        //RETORNA A PAGINA COMPLETA
        return parent::getPage('Reclamação User',$content);
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