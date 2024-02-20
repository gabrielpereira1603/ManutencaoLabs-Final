<?php

namespace app\Controller\Pages;

use \app\Utils\View;

class Alert{

    /**
     * Metodo responsavel por retornar uma mensagem de erro
     * @param string
     * @return string
     */
    public static function getError($message){
        return View::render('Pages/alert/status',[
            'tipo' => 'danger',
            'mensagem' => $message
        ]);
    }

    /**
     * Metodo responsavel por retornar uma mensagem de sucesso
     * @param string
     * @return string
     */
    public static function getSuccess($message){
        return View::render('Pages/alert/status',[
            'tipo' => 'success',
            'mensagem' => $message
        ]);
    }
}