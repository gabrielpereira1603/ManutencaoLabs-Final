<?php

namespace app\Controller\Api;

/**
 * Metodo responsavel por retornar os detalhes da API
 * @param Request
 * @return array
 */
class Api {
    public static function getDetails($request) {
        return [
            'nome'   => 'API - Somos Devs',
            'versao' =>'v1.0.0',
            'autor'  => 'Gabriel Pereira',
            'email'  => 'pereiragabrieldev@gmail.com',
        ];
    }
}