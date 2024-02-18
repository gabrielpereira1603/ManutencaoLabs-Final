<?php

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class User {

    /**
     * ID do usuario
     * @var integer
     */
    public $id;

     /**
      * Nome do usuario
      * @var string
      */
    public $nome;

    /**
     * Email do usuario
     * @var string
     */
    public $email;

    /**
     * Senha do usuario
     * @var string
     */
    public $senha;

    /**
     * Metodo responsavel por retorna o usuario com base em seu email
     * @param string
     * @return User
     */
    public static function getUserByEmail($email){
        return (new Database('usuarios'))->select('email = "'.$email.'"')->fetchObject(self::class);
    }

}
