<?php
namespace app\Controller\Admin;

use \app\Model\Entity;
use \app\Utils\View;
use \app\Model\Entity\NivelAcesso as EntityNivelAcesso;
use \app\Model\Entity\User as EntityUser;


class User extends Page
{

    /**
     * Metodo responsavel por trazer os usuarios cadastrados
     * @param Request
     * @return string
     */
    public static function getAll($request)
    {
        $itens = '';
        $result = EntityUser::getAllUser();
        foreach ($result as $obUsuario) {
            $itens .= View::render('admin/usuarios/item', [
                'codusuario' => $obUsuario['codusuario'],
                'nome_usuario' => $obUsuario['nome_usuario'],
            ]);
        }
        return $itens;
    }

    public static function NotPermissao($request)
    {
        $itens = '';
        $result = EntityUser::getNotPermissao();
        foreach ($result as $obUsuario) {
            $itens .= View::render('admin/usuarios/semPermissao', [
                'login' => $obUsuario['login'],
                'nome_usuario' => $obUsuario['nome_usuario'],
                'email_usuario' => $obUsuario['email_usuario'],
            ]);
        }
        return $itens;
    }

    /**
     * Metodo responsavel por trazer informacoes do usuario
     * @param Request
     * @return string
     */
    public static function getUserItens($request)
    {
        $itens = '';
        $result = EntityNivelAcesso::getNivelAcesso();

        foreach ($result as $obNivelAcesso) {
            $itens .= View::render('admin/nivel_acesso/item', [
                'tipo_acesso' => $obNivelAcesso['tipo_acesso'],
                'codnivel_acesso' => $obNivelAcesso['codnivel_acesso'],
            ]);
        }

        return $itens;
    }


    /**
     * Metodo responsavel por renderizar a view de gerenciamento de users
     * @param Request
     * @return string
     */
    public static function getUser($request)
    {
        // Obtém os dados do usuário logado
        $userData = $_SESSION['admin']['usuario'];
        $codUsuario = $userData['codusuario'];
        $tipousuario = $userData['tipo_acesso'];

        $content = View::render('admin/modules/user/index', [
            'codusuario' => $codUsuario,
            'tipouser' => $tipousuario,
        ]);

        //RETONA A PAGINA COMPLETA
        return parent::getPanel('Usuários', $content, 'user');
    }

    /**
     * Metodo responsavek por rendererizar a view de add user
     * @param Request
     * @return string
     */
    public static function getNewUser($request, $errorMessage = null)
    {
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

        $content = View::render('admin/modules/user/addUser', [
            'nivel_acesso' => self::getUserItens($request),
            'status' => $status,
        ]);

        return parent::getPanel('Adicionar Usuário', $content, 'user');
    }

    /**
     * Metodo responsavel por criar novo usuario
     * @param Request
     * @return string
     */
    public static function setNewUser($request)
    {
        $userData = $_SESSION['admin']['usuario'];
        $codUsuario = $userData['codusuario'];
        $nivel_acesso_Session = $userData['nivel_acesso'];

        $postVars = $request->getPostVars();

        $login = $postVars['login'] ?? '';
        $email = $postVars['email'] ?? '';
        $nome = $postVars['nome'] ?? '';
        $nivel_acesso = $postVars['nivel_acesso'] ?? '';
        $senha = $postVars['senha'] ?? '';
        $hashed_password = password_hash($senha, PASSWORD_DEFAULT);
        

        $obUser = EntityUser::getUserByLogin($login);
        if (!$obUser == null) {
            return self::getNewUser($request, 'Login existente, Insira outro login!');
        }

        if ($nivel_acesso_Session <= 2) {
            return self::getNewUser($request, 'Voçê não tem permissão para cadastrar usuário!');
        }

        // Cria uma nova instância da entidade User
        $obNewUser = new EntityUser();

        // Define os atributos do usuario
        $obNewUser->login = $login;
        $obNewUser->email_usuario = $email;
        $obNewUser->senha = $hashed_password;
        $obNewUser->nome_usuario = $nome;
        $obNewUser->nivel_acesso_fk = $nivel_acesso;

        // Chama o método para cadastrar o User
        $obNewUser->setNewUser();
        if ($obNewUser = true) {
            $request->getRouter()->redirect('/admin/user/add?success=add');
        } else {
            $request->getRouter()->redirect('/admin/user/add?error=not');
        }
    }

    /**
     * Metodo responsavel por rederizar a view de Acesso
     */
    public static function getAcesso($request, $errorMessage = null)
    {
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';
        $content = View::render('admin/modules/user/acessoUser', [
            'nivel_acesso' => self::getUserItens($request),
            'usuarios' => self::getAll($request),
            'not-permissao' => self::NotPermissao($request),
            'status' => $status,
        ]);

        return parent::getPanel('Permissões de Usuários', $content, 'user');
    }

    /**
     * Metodo responsavel por remover ou conceder acesso ao usuario
     */
    public static function setAcesso($request, $errorMessage = null)
    {
        $postVars = $request->getPostVars();

        $login = $postVars['login'] ?? '';
        $email = $postVars['email'] ?? '';
        $nome = $postVars['nome'] ?? '';
        $nivel_acesso = $postVars['nivel_acesso'] ?? '';

        $obNewAcesso = new EntityUser();

        $obNewAcesso->login = $login;
        $obNewAcesso->email_usuario = $email;
        $obNewAcesso->nome_usuario = $nome;
        $obNewAcesso->nivel_acesso_fk = $nivel_acesso;

        // Chama o método para cadastrar o User
        $obNewAcesso->setNewAcesso($login, $nivel_acesso);
        if ($obNewAcesso = true) {
            $request->getRouter()->redirect('/admin/user/acesso?success=permissaoAdd');
        } else {
            $request->getRouter()->redirect('/admin/user/acesso?error=permissaoNot');
        }
    }

    /**
     * Metodo responsavel por renderizar a view de alterar dados do usuario
    */
    public static function getUpdate($request) {
        $userData = $_SESSION['admin']['usuario'];
        $nomeUser = $userData['nome_usuario'];
        $tipo_acesso_Session = $userData['tipo_acesso'];
        $emailUser = $userData['email_usuario'];
        $loginUser = $userData['login'];

        $content = View::render('admin/modules/user/updateUser', [
            'nivel_acesso' => $tipo_acesso_Session,
            'nomeUser' => $nomeUser,
            'emailUser' => $emailUser,
            'loginUser' => $loginUser,
            'usuarios' => self::getAll($request),
        ]);

        return parent::getPanel('Alterar Usuários', $content, 'user');
    } 

    /**
     * Metodo responsavel por alterar os dados do usuarios
     */
    public static function setUpdate($request)
    {
        $postVars = $request->getPostVars();
        $nome = $postVars['nome'] ?? '';    
        $email = $postVars['email'] ?? '';
        $login = $postVars['login'] ?? '';

        if($login == null) {
            $request->getRouter()->redirect('/admin/user/update?success=prenchaLogin');
        }
        
        $codUsuario = EntityUser::getUserByLogin($login);
        

        $obUpdateUser = new EntityUser();

        $obUpdateUser->nome_usuario = $nome;
        $obUpdateUser->email_usuario = $email;
        $obUpdateUser->login = $login;
        $result = EntityUser::setUpdateUser($request);

    }
    
}

