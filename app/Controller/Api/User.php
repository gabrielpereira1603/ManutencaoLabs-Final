<?php

namespace app\Controller\Api;
use \app\Model\Entity\User as EntityUser;

/**
 * Metodo responsavel por retornar os detalhes da API
 * @param Request
 * @return array
 */
class User extends Api {
    // public static function getUsers($request) {
    //     // Chama o método da model para buscar todos os usuários
    //     $itens =[];
    //     $results = EntityUser::getAllUserAdmin();


    //     while($obUser = $results->fetchObject(EntityUser::class)){
    //         $itens[] = [
    //             'nome_usuario' => $obUser->nome_usuario,
    //             'email_usuario' => $obUser->email_usuario,
    //             'tipo_acesso' => $obUser->tipo_acesso,
    //             'login' => $obUser->login,
    //         ];
    //     }
    //     // Retorna os usuários em formato JSON
    //     return $itens;
    // }

    public static function getUserByID($request,$codusuario) {
        // Chama o método da model para buscar todos os usuários
        $itens =[];
        $results = EntityUser::getUserByID($codusuario);


        while($obUser = $results->fetchObject(EntityUser::class)){
            $itens[] = [
                'nome' => $obUser->nome,
                'email' => $obUser->email,
                'tipo_acesso' => $obUser->tipo_acesso,
                'login' => $obUser->login,
            ];
        }
        // Retorna os usuários em formato JSON
        return $itens;
    }
}