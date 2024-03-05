<?php

namespace app\Controller\Api;

/**
 * Metodo responsavel por retornar os detalhes da API
 * @param Request
 * @return array
 */
class Computador extends Api {

    private static function getComputadoresItems($request,&$obPagination){
        // Computadores
        $itens = [];
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

        // Resultados da página
        $results = EntityComputador::getComputadoresLaboratorioPagination($codlaboratorio, $obPagination, $obPagination->getLimit());
        //RENDERIZA O ITEM
        while ($obComputador = $results->fetchObject(EntityComputador::class)) {
            // Atualizar os contadores com base no tipo de situação do computador
            switch ($obComputador->codsituacao) {
                case 1:
                    $status = 'status-itens status-item-2 btn btn-success';
                    $icone = 'bi bi-check-circle-fill'; // Ícone de computador disponível
                    break;
                case 2:
                    $status = 'status-itens status-item-1 btn btn-danger';
                    $icone = 'bi bi-tools'; // Ícone de computador em manutenção
                    break;
                case 3:
                    $status = 'status-itens status-item-3 btn btn-warning';
                    $icone = 'bi bi-exclamation-octagon-fill'; // Ícone de computador indisponível
                    break;
                default:
                    $icone = ''; // Ícone padrão, caso não haja correspondência
                    break;
            }

    
            $itens[] = [
                'codcomputador' => $obComputador->codcomputador,
                'patrimonio' => $obComputador->patrimonio,
                'codlaboratorio' =>$codlaboratorio,
                'laboratorio' => $obComputador->numerolaboratorio,
                'situacao' => $obComputador->tiposituacao,
                'icone' => $icone,
                'status' => $status
            ];
        }

        //RETORNA OS DEPOIMENTOS
        return $itens;
    }
    
    public static function getComputadores($request) {
        return [
            'patrimonio' => [],
            'codlaboratorio_fk' => [],
            'codsituacao_fk' => [],
            'paginacao' => [],            
        ];
    }
}