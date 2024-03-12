<?php

namespace app\Controller\Api;
use \app\Model\Entity\Reclamacao as EntityReclamacao;

/**
 * Metodo responsavel por retornar os detalhes da API
 * @param Request
 * @return array
 */
class Reclamacao extends Api {
    // public static function getReclamacao($request) {
    //     return [
    //         'patrimonio' => [],
    //         'codlaboratorio_fk' => [],
    //         'codsituacao_fk' => [],
    //         'paginacao' => [],            
    //     ];
    // }

    public static function getReclamacaoPorLab($request) {
        $itens =[];
        $results = EntityReclamacao::reclamacaoPorLab();

        while($obReclamacao = $results->fetchObject(EntityReclamacao::class)){
            $itens[] = [
                'total_reclamacoes' => $obReclamacao->total_reclamacoes,
                'numerolaboratorio' => $obReclamacao->numerolaboratorio,
                'codlaboratorio' => $obReclamacao->codlaboratorio,
            ];
        }
        // Retorna os usuários em formato JSON
        return $itens;
    }
}