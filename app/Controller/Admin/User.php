<?php 
namespace app\Controller\Admin;

use \app\Model\Entity;
use \app\Utils\View;
use \app\Model\Entity\NivelAcesso as EntityNivelAcesso;
use \app\Model\Entity\User as EntityUser;


class User extends Page {

    /**
     * Metodo responsavel por trazer os usuarios cadastrados
     * @param Request
     * @return string
     */
    public static function getAll($request) {
        $itens = '';
        $result = EntityUser::getAllUser();
        foreach ($result as $obUsuario){
            $itens .= View::render('admin/usuarios/item', [
                'codusuario'=> $obUsuario['codusuario'],
                'nome_usuario'=> $obUsuario['nome_usuario'],
            ]);
        }
        return $itens;
    }

    public static function NotPermissao($request) {
        $itens = '';
        $result = EntityUser::getNotPermissao();
        foreach ($result as $obUsuario){
            $itens .= View::render('admin/usuarios/semPermissao', [
                'login'=> $obUsuario['login'],
                'nome_usuario'=> $obUsuario['nome_usuario'],
                'email_usuario'=> $obUsuario['email_usuario'],
            ]);
        }
        return $itens;
    }

    /**
     * Metodo responsavel por trazer informacoes do usuario
     * @param Request
     * @return string
     */
    public static function getUserItens($request) {
        $itens = '';
        $result = EntityNivelAcesso::getNivelAcesso();

        foreach ($result as $obNivelAcesso){
            $itens .= View::render('admin/nivel_acesso/item', [
                'tipo_acesso'=> $obNivelAcesso['tipo_acesso'],
                'codnivel_acesso'=> $obNivelAcesso['codnivel_acesso'],
            ]);
        }

        return $itens;  
    }


    /**
     * Metodo responsavel por renderizar a view de gerenciamento de users
     * @param Request
     * @return string
    */
    public static function getUser($request){
        // Obtém os dados do usuário logado
        $userData = $_SESSION['admin']['usuario'];
        $codUsuario = $userData['codusuario'];
        $tipousuario = $userData['tipo_acesso'];

        $content = View::render('admin/modules/user/index',[
            'codusuario' => $codUsuario,
            'tipouser'=> $tipousuario,
        ]);

        //RETONA A PAGINA COMPLETA
        return parent::getPanel('Usuários', $content, 'user');
    }

    /**
     * Metodo responsavek por rendererizar a view de add user
     * @param Request
     * @return string
    */
    public static function getNewUser($request, $errorMessage = null){
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

        $content = View::render('admin/modules/user/addUser', [
            'nivel_acesso' => self::getUserItens($request),
            'status' => $status,
        ]);

        return parent::getPanel('Adicionar Usuário', $content,'user');
    }

    /**
     * Metodo responsavel por criar novo usuario
     * @param Request
     * @return string
    */
    public static function setNewUser($request) {
        $userData = $_SESSION['admin']['usuario'];
        $codUsuario = $userData['codusuario'];
        $nivel_acesso_Session = $userData['nivel_acesso'];

        $postVars = $request->getPostVars();

        $login = $postVars['login'] ?? '';
        $email = $postVars['email'] ?? '';
        $nome  = $postVars['nome'] ?? '';
        $nivel_acesso = $postVars['nivel_acesso'] ??'';
        $senha = $postVars['senha'] ?? '';
        $hashed_password = password_hash($senha, PASSWORD_DEFAULT);
        
        $obUser = EntityUser::getUserByLogin($login);
        if(!$obUser == null){ 
            return self::getNewUser($request, 'Login existente, Insira outro login!');
        }

        if($nivel_acesso_Session <= 2){
            return self::getNewUser($request,'Voçê não tem permissão para cadastrar usuário!');
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
        if($obNewUser = true){
            $request->getRouter()->redirect('/admin/user/add?success=add');
        }else{
            $request->getRouter()->redirect('/admin/user/add?error=not');
        }
    }

    /**
     * Metodo responsavel por rederizar a view de Acesso
     */
    public static function getAcesso($request,$errorMessage = null){
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';
        $content = View::render('admin/modules/user/acessoUser', [
            'nivel_acesso' => self::getUserItens($request),
            'usuarios' => self::getAll($request),
            'not-permissao' => self::NotPermissao($request),
            'status' => $status,
        ]);

        return parent::getPanel('Permissões de Usuários', $content,'user');
    }

    /**
     * Metodo responsavel por remover ou conceder acesso ao usuario
     */
    public static function setAcesso($request, $errorMessage = null){
    
    }
}
