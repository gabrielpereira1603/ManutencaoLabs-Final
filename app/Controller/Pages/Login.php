<?php

namespace app\Controller\Pages;

use \app\Model\Entity\User; 
use \app\Utils\View;
use \app\Session\User\Login as SessionUserLogin;

class Login extends Page{
    
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
        $content = View::render('Pages/login',[
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
        //ATRIBUI OS VALORES DA REQUISICAO AS VARIAVEIS
        $loginUsuario = $obUser[0]['login'];
        $senhaUsuario = $obUser[0]['senha'];
        $CodNivelAcesso = $obUser[0]['codnivel_acesso'];

        // Verifica se o usuário foi encontrado e se a senha está correta
        if ($loginUsuario != $login) {
            // O usuário não foi encontrado ou o login está incorreto
            $request->getRouter()->redirect('/login?error=loginError');        
        }

        if($CodNivelAcesso == 2 || $CodNivelAcesso == 3 || $CodNivelAcesso == 4) {
            $request->getRouter()->redirect('/login?error=loginErrorPermissao');
        }

        //VERIFICA A SENHA DO USUARIO
        if(!password_verify($senha, $senhaUsuario)) {
            return self::getLogin($request, 'Login ou Senha inválidos');
        }

        //CRIA A SESSAO DE LOGIN
        SessionUserLogin::login($obUser);

        //REDIRECIONA O USUARIO PARA A HOME DO USER
        $request->getRouter()->redirect('/');
    }

    /**
     * Metodo responsavel por desloga o usuario
     * @param //Request
     */
    public static function setLogout($request){
        //DESTRO A SESSAO DE LOGIN
        SessionUserLogin::logout();

        //REDIRECIONA O USUARIO PARA A TELA DE LOGIN
       $request->getRouter()->redirect('/login');
    }
}