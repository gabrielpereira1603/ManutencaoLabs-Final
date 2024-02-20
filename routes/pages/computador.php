<?php

use \app\Http\Response;
use \app\Controller\Pages;

//ROTA DE INSERIR RECLAMACAO (GET)
$obRouter->get('/computador/{codlaboratorio}',[
    // 'middlewares' => [
    //     'required-admin-login'
    // ],
    function($request,$codlaboratorio) {
        return new Response(200,Pages\Computador::getComputador($request,$codlaboratorio));
    }
]);