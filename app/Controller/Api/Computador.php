<?php

namespace app\Controller\Api;

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
}