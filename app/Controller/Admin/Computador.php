<?php

namespace app\Controller\Admin;

use \app\Model\Entity\Computador as EntityComputador; 
use \app\Model\Entity\Laboratorio as EntityLaboratorio;
use \app\Model\Entity\Situacao as EntitySituacao;
use WilliamCosta\DatabaseManager\Pagination;
use \app\Controller\Api;
use app\Controller\Api\Computador as ApiComputador;
use \app\Utils\View;

class Computador extends Page{   
    private static function getComputadorItems($request,$codlaboratorio,&$obPagination){
        // Computadores
        $itens = '';
        // Ícones de status
        $icones = '';
        // Classe do ícone
        $status = '';

        // Quantidade total de registros
        $quantidadetotal = EntityComputador::getQuantidadeComputadores($codlaboratorio);
        
        // Página atual
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        // Instância de paginação
        $obPagination = new Pagination($quantidadetotal, $paginaAtual,10);
        
        // Obtém o limite e o offset
        $limit = $obPagination->getLimit();
        $offset = $obPagination->getOffset();
        
        $results = EntityComputador::getComputadoresLaboratorioPagination($codlaboratorio, $obPagination, $limit, $offset);
        //RENDERIZA O ITEM
        while ($obComputador = $results->fetchObject(EntityComputador::class)) {
            // Atualizar os contadores com base no tipo de situação do computador
            switch ($obComputador->codsituacao) {
                case 1:
                    $status = 'status-itens status-item-2 btn btn-success';
                    $icone = 'bi bi-check-circle-fill'; // Ícone de computador em Disponível
                    break;
                case 2:
                    $status = 'status-itens status-item-1 btn btn-warning';
                    $icone = 'bi bi-tools'; // Ícone de computador Em Manutenção
                    break;
                case 3:
                    $status = 'status-itens status-item-3 btn btn-danger';
                    $icone = 'bi bi-exclamation-octagon-fill'; // Ícone de computador Indisponível
                    break;
                default:
                    $icone = ''; // Ícone padrão, caso não haja correspondência
                    break;
            }
        
            $itens .= View::render('admin/computador/item', [
                'codcomputador' => $obComputador->codcomputador,
                'patrimonio' => $obComputador->patrimonio,
                'codlaboratorio' => $obComputador->codlaboratorio,
                'laboratorio' => $obComputador->numerolaboratorio,
                'situacao' => $obComputador->tiposituacao,
                'icone' => $icone,
                'status' => $status
            ]);
        }
        

        //RETORNA OS DEPOIMENTOS
        return $itens;
    }

    /**
     * Metodo reponsavel por buscar os computadores 
     */
    public static function getComputador($request,$codlaboratorio) {
        // Obtém as informações do laboratório
        $numeroLaboratorio = EntityLaboratorio::getNumeroLaboratorio($codlaboratorio);
        while ($obLaboratorio = $numeroLaboratorio->fetchObject(EntityComputador::class)) {
            $NumeroLaboratorio =  $obLaboratorio->numerolaboratorio;
            //CONTEUDO DA PAGINA DE RECLAMACAO
            $content = View::render('admin/modules/computadoresManutencao/index', [
                'nav' => parent::getNav($request),
                'itens' => self::getComputadorItems($request, $codlaboratorio, $obPagination),
                'pagination' => parent::getPagination($request, $obPagination),
                'codlaboratorio' => $codlaboratorio,
                'numerolaboratorio' => $NumeroLaboratorio,
            ]);
            
            //RETORNA A PAGINA COMPLETA
            return parent::getPage('Computadores',$content);
        }
    }

}