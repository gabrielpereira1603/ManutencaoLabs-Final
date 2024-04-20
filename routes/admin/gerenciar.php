<?php

use \app\Http\Response;
use \app\Controller\Admin;

//ROTA DE INSERIR RECLAMACAO (GET)
$obRouter->get('/admin/gerenciar',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200,Admin\Gerenciar::getGerenciar($request));
    }
]);