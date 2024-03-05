<?php

namespace app\Session\Admin;

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
     * 
     * @return boolean
     */
    public static function login($obUser){
        //INICIA A SESSAO
        self::init();

        $loginUsuario = $obUser[0]['login'];
        $senhaUsuario = $obUser[0]['senha'];
        $CodNivelAcesso = $obUser[0]['codnivel_acesso'];

        //DEFINE A SESSAO DO USUARIO
        $_SESSION['admin']['usuario'] = [
            'codusuario' => $obUser[0]['codusuario'],
            'nome_usuario'=> $obUser[0]['nome_usuario'],
            'email_usuario'=> $obUser[0]['email_usuario'],
            'login' => $obUser[0]['login'],
            'nivel_acesso' => $obUser[0]['nivelacesso_fk'],
            'tipo_acesso' => $obUser[0]['tipo_acesso']
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
        return isset($_SESSION['admin']['usuario']['codusuario']);
    }

    /**
     * Metodo responsavel por executar logout do usuario
     * @return boolean
     */
    public static function logout(){
        //INICIA A SESSAO
        self::init();

        //DESLOGA O USUARIO
        unset($_SESSION['admin']['usuario']);
        
        //SUCESSO
        return true;
    }
}