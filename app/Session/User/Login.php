<?php

namespace app\Session\User;

class Login {

    /**
     * Metodo responsavel por iniciar a sessao
     */
    private static function init(){
        //VERIFICA SE A SESSAO NAO ESTA ATIVA
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();    
        }
    }
    /**
     * Metodo responsavel por criar o login do usuario
     * @param User
     * @return boolean
     */
    public static function login($obUser){
        //INICIA A SESSAO
        self::init();

        //DEFINE A SESSAO DO USUARIO
        $_SESSION['user']['usuario'] = [
            'codusuario' => $obUser->codusuario,
            'nome_usuario'=> $obUser->nome_usuario,
            'email_usuario'=> $obUser->email_usuario,
            'login' => $obUser->login,
            'nivel_acesso' => $obUser->nivel_acesso_fk,
            'tipo_acesso'=> $obUser->tipo_acesso,
        ];

        //SUCESSO
        return true;
    }

    /**
     * Metodo reponsavel por verificar se o usuario esta logado
     * @return boolean 
     * */    
    public static function isLogged(){
        //INICIA A SESSAO
        self::init(); 

        //RETORNA A VERIFICACAO
        return isset($_SESSION['user']['usuario']['codusuario']);
    }

    /**
     * Metodo responsavel por executar logout do usuario
     * @return boolean
     */
    public static function logout(){
        //INICIA A SESSAO
        self::init();

        //DESLOGA O USUARIO
        unset($_SESSION['user']['usuario']);
        
        //SUCESSO
        return true;
    }
}