<?php

namespace app\Controller\Api;
use \app\Model\Entity\User as EntityUser;

/**
 * Metodo responsavel por retornar os detalhes da API
 * @param Request
 * @return array
 */
class User extends Api {
    public static function getUsers($request) {
        // Chama o método da model para buscar todos os usuários
        $users = EntityUser::getAllUser();

        // Retorna os usuários em formato JSON
        return json_encode($users);
    }

    public static function getUserByID($request,$codusuario) {
        // Chama o método da model para buscar todos os usuários
        $users = EntityUser::getUserByID($codusuario);
        // Retorna os usuários em formato JSON
        return json_encode($users);
    }
}