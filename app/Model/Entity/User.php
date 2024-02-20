<?php

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class User {

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
     * Método responsável por retornar o usuário com base em seu email
     * @param string $email
    //  * @return User|false
     */
    public static function getUserByLogin($login){
      // Condição para selecionar os computadores pelo código do laboratório
      $where = "usuario.login = $login";

      //PEGAR OS DADOS DA SITUACAO COM INNERJOIN
      $join = 'INNER JOIN nivel_acesso ON usuario.nivel_acesso_fk = nivel_acesso.codnivel_acesso';
      return (new Database('usuario'))->select($where, null, null, '*', $join)->fetchObject(self::class);
    }
}
