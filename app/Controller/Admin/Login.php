<?php

namespace app\Controller\Admin;

use \app\Model\Entity\User; 
use \app\Utils\View;
use \app\Session\Admin\Login as SessionAdminLogin;

class Login extends Page {
    
    /**
     * Metodo responsavel por retornar a redenrizacao da pagina de login
     * @param //Request
     * @param string
     * @return string
     */
    public static function getLogin($request, $errorMessage = null) {
        //STATUS
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

        //CONTEUDO DA PAGINA DE LOGIN
        $content = View::render('admin/login',[
            'status' => $status
        ]);

        //RETORNA A PAGINA COMPLETA
        return parent::getPage('Login > Somos Devs',$content);
    }

    /**
     * Metodo responsavel por definir o login do usuario
     * @param Request
     */
    public static function setLogin($request){
        //POST VARS
        $postVars = $request->getPostVars();
        $login = $postVars['login'] ?? '';
        $senha = $postVars['senha'] ?? '';
        

        //BUSCA USUARIO PELO LOGIN
        $obUser = User::getUserByLogin($login);
        if(!$obUser instanceof User){
            return self::getLogin($request, 'Login ou Senha inválidos');
        }

        if($obUser->codnivel_acesso == 1 || $obUser->codnivel_acesso == 4) {
            return self::getLogin($request, 'Você não tem permissão para entrar!');
        }

        //VERIFICA A SENHA DO USUARIO
        if(!password_verify($senha, $obUser->senha)) {
            return self::getLogin($request, 'Login ou Senha inválidos');
        }

        //CRIA A SESSAO DE LOGIN
        SessionAdminLogin::login($obUser);

        //REDIRECIONA O USUARIO PARA A HOME DO ADMIN
        $request->getRouter()->redirect('/admin');
    }

    /**
     * Metodo responsavel por desloga o usuario
     * @param //Request
     */
    public static function setLogout($request){
        //DESTRO A SESSAO DE LOGIN
        SessionAdminLogin::logout();

        //REDIRECIONA O USUARIO PARA A TELA DE LOGIN
       $request->getRouter()->redirect('/admin/login');
    }
}