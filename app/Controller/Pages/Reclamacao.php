<?php

namespace app\Controller\Pages;

use \app\Model\Entity\Computador as EntityComputador; 
use \app\Model\Entity\Componente as EntityComponente; 
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

        
        // var_dump($paginaAtual);

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
        //POST VARS
        $postVars = $request->getPostVars();

        $foto = $_FILES['foto-reclamacao'] ?? '';
        $descricaoManutencao = $postVars['descricao'] ?? '';
        $componente = $postVars['componente'] ?? '';
        var_dump($componente, $descricaoManutencao,$foto);

    }
}