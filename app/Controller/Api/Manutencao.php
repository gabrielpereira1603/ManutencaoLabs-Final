<?php

namespace app\Controller\Api;
use \app\Model\Entity\Manutencao as EntityManutencao;

/**
 * Metodo responsavel por retornar os detalhes da API
 * @param Request
 * @return array
 */
class Manutencao extends Api {
    public static function getManutencaoPorUser($request) {
        $itens =[];
        $results = EntityManutencao::ManutencaoPorUser();

        while($obReclamacao = $results->fetchObject(EntityManutencao::class)){
            $itens[] = [
                'total_manutencoes' => $obReclamacao->total_manutencoes,
                'nome_usuario' => $obReclamacao->nome_usuario,
            ];
        }
        // Retorna os usuários em formato JSON
        return $itens;
    }
}