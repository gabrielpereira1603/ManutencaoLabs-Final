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
        //CONTEUDO DA PAGINA DE LOGIN
        $content = View::render('admin/login',[
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
        // Verifica se o array obUser não está vazio
        if (empty($obUser)) {
            // O usuário não foi encontrado ou o login está incorreto
            $request->getRouter()->redirect('/login?error=loginError');
            return; // Interrompe a execução do método
        }
        //ATRIBUI OS VALORES DA REQUISICAO AS VARIAVEIS
        $loginUsuario = $obUser[0]['login'];
        $senhaUsuario = $obUser[0]['senha'];
        $CodNivelAcesso = $obUser[0]['codnivel_acesso'];

        // Verifica se o usuário foi encontrado e se a senha está correta
        if ($loginUsuario != $login) {
            // O usuário não foi encontrado ou o login está incorreto
            $request->getRouter()->redirect('/admin/login?error=loginError');        
        }

        if($CodNivelAcesso == 1 || $CodNivelAcesso == 4) {
            $request->getRouter()->redirect('/admin/login?error=loginErrorPermissao');
        }

        //VERIFICA A SENHA DO USUARIO
        if(!password_verify($senha, $senhaUsuario)) {
            $request->getRouter()->redirect('/admin/login?error=loginError');  
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