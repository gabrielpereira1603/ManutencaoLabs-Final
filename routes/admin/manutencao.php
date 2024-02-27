<?php

use \app\Http\Response;
use \app\Controller\Admin;


//ROTA MANUTENCAO
$obRouter->get('/admin/manutencao/{codcomputador}',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$codcomputador) {
        return new Response(200,Admin\Manutencao::getManutencao($request,$codcomputador));
    }
]);

//ROTA MANUTENCAO
$obRouter->post('/admin/manutencao/{codcomputador}',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$codcomputador) {
        return new Response(200,Admin\Manutencao::setManutencao($request,$codcomputador));
    }
]);
