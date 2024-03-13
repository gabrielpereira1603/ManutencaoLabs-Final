<?php

use \app\Http\Response;
use \app\Controller\Admin;

//ROTA DE RELATORIO
$obRouter->get('/relatorio',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200,Admin\Relatorio::getRelatorio($request));
    }
]);