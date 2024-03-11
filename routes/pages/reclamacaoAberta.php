<?php

use \app\Http\Response;
use \app\Controller\Pages;

//ROTA DE RECLAMACOES EM ABERTO 
$obRouter->get('/reclamacoesAbertas',[
    'middlewares' => [
        'required-user-login'
    ],
    function($request) {
        return new Response(200,Pages\Reclamacao::getReclamacaoAbertas($request));  
    }
]);