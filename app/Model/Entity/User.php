<?php

namespace app\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User
{

  /**
   * ID do usuário
   * @var integer
   */
  public $codusuario;

  /**
   * Login do usuário
   * @var string
   */
  public $login;

  /**
   * Senha do usuário
   * @var string
   */
  public $senha;

  /**
   * Nome do usuário
   * @var string
   */
  public $nome_usuario;

  /**
   * Email do usuário
   * @var string
   */
  public $email_usuario;

  /**
   * Token de redefinição de senha
   * @var string
   */
  public $reset_token;

  /**
   * Token de autenticação
   * @var string
   */
  public $token;

  /**
   * Data de expiração do token de redefinição de senha
   * @var string
   */
  public $reset_expires;

  /**
   * Nível de acesso do usuário
   * @var integer
   */
  public $nivel_acesso_fk;

  /**
   * Cod de acesso do usuário
   * @var integer
   */
  public $codnivel_acesso;

  /**
   * Nível de acesso do usuário
   * @var string
   */
  public $tipo_acesso;

    /**
   * Metodo responsavel por verificar o usuario pelo login
   */
  public static function getUserByLogin($login)
  {
    $where = "usuario.login = '$login'";

    $join = 'INNER JOIN nivel_acesso ON usuario.nivelacesso_fk = nivel_acesso.codnivel_acesso';

    return (new Database('usuario'))->select($where,null,null,'*',$join)->fetchAll();
  }
  
  /**
   * Metodo responsavel por criar um novo usuario
   */
  public function setNewUser()
  {
    $database = new Database('usuario');
    $this->codusuario = $database->insert([
      'login' => $this->login,
      'email_usuario' => $this->email_usuario,
      'senha' => $this->senha,
      'nome_usuario' => $this->nome_usuario,
      'nivel_acesso_fk' => $this->nivel_acesso_fk
    ]);
    return true;
  }

  /**
   * Metodo responsavel por trazer todos os usuarios
   */
  public static function getAllUser()
  {
    return (new Database('usuario'))->select()->fetchAll();
  }

  /**
   * Metodo responsavel por trazer os usuario sem permissao no sistema
   * 
   */
  public static function getNotPermissao()
  {
    $where = "usuario.nivel_acesso_fk = 4";
    return (new Database('usuario'))->select($where)->fetchAll();
  }

  /**
   * Metodo responsavel por alterar o Acesso do usuario
   */
  public static function setNewAcesso($login, $nivel_acesso)
  {
    // Construct the WHERE clause to identify the user record to update
    $where = "login = '$login'";

    // Construct the array of fields to update with the new access level
    $values = ['nivel_acesso_fk' => $nivel_acesso];

    // Call the update method with the WHERE clause and the array of fields to update
    $result = (new Database('usuario'))->update($where, $values);

    // Check the result of the update operation
    if ($result) {
        // Update was successful, handle accordingly (e.g., return true, redirect, etc.)
        return true;
    } else {
        // Update failed, handle accordingly (e.g., return false, set error message, etc.)
        return false;
    }
  }

  /**
   * Metodo reponsavel por atualizar as informacoes do usuario no banco 
   */
  public static function setUpdateUser($request) 
  {

  }
}
