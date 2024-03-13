<?php

namespace app\Controller\Api;
use \app\Model\Entity\Componente as EntityComponente;

/**
 * Metodo responsavel por retornar os detalhes da API
 * @param Request
 * @return array
 */
class Componente extends Api {
    public static function getComponenteReclamacao($request,$codreclamacao) {
        // Chama o método da model para buscar todos os usuários
        $itens =[];

        $results = EntityComponente::ComponenteReclamacao($codreclamacao);


        while($obComponente = $results->fetchObject(EntityComponente::class)){
            $itens[] = [
                'codcomponente' => $obComponente->codcomponente,
                'nome_componente' => $obComponente->nome_componente,
            ];
        }
        // Retorna os usuários em formato JSON
        return $itens;
    }

    public static function getComponente($request) {
        // Chama o método da model para buscar todos os usuários
        $itens =[];

        $results = EntityComponente::getComponentes();


        while($obComponente = $results->fetchObject(EntityComponente::class)){
            $itens[] = [
                'codcomponente' => $obComponente->codcomponente,
                'nome_componente' => $obComponente->nome_componente,
            ];
        }
        // Retorna os usuários em formato JSON
        return $itens;
    }
}