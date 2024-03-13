<?php

use \app\Http\Response;
use \app\Controller\Pages;

//ROTA DE INSERIR RECLAMACAO (GET)
$obRouter->get('/reclamacao/{codcomputador}',[
    'middlewares' => [
        'required-user-login'
    ],
    function($request,$codcomputador) {
        return new Response(200,Pages\Reclamacao::getReclamacao($request,$codcomputador));
    }
]);

//ROTA DE INSERIR RECLAMACAO (post)
$obRouter->post('/reclamacao/{codcomputador}',[
    'middlewares' => [
        'required-user-login'
    ],
    function($request,$codcomputador) {
        return new Response(200,Pages\Reclamacao::setReclamacao($request,$codcomputador));
    }
]);

//ROTA DE INSERIR RECLAMACAO (post)
$obRouter->post('/reclamacoesAbertas',[
    'middlewares' => [
        'required-user-login'
    ],
    function($request) {
        return new Response(200,Pages\Reclamacao::setUpdateReclamacao($request));
    }
]);

//ROTA DE INSERIR RECLAMACAO (post)
$obRouter->post('/reclamacoesAbertas/delete',[
    'middlewares' => [
        'required-user-login'
    ],
    function($request) {
        return new Response(200,Pages\Reclamacao::setDeleteReclamacao($request));
    }
]);