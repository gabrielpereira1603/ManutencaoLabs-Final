<?php

namespace app\Controller\Api;
use \app\Model\Entity\Computador as EntityComputador;

/**
 * Metodo responsavel por retornar os detalhes da API
 * @param Request
 * @return array
 */
class Computador extends Api {
    public static function getComputadores($request) {
        return [
            'patrimonio' => [],
            'codlaboratorio_fk' => [],
            'codsituacao_fk' => [],
            'paginacao' => [],            
        ];
    }

    public static function getComputadoresPorLab($request,$codlaboratorio) {
        // Chama o método da model para buscar todos os usuários
        $itens =[];

        $results = EntityComputador::getComputadoresLaboratorio($codlaboratorio);


        while($obComputador = $results->fetchObject(EntityComputador::class)){
            $itens[] = [
                'codcomputador' => $obComputador->codcomputador,
                'patrimonio' => $obComputador->patrimonio,
                'tiposituacao' => $obComputador->tiposituacao,
            ];
        }
        // Retorna os usuários em formato JSON
        return $itens;
    }
}