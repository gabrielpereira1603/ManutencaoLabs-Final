<?php

use \app\Http\Response;
use \app\Controller\Pages;

//ROTA HOME
$obRouter->get('/',[
    'middlewares' => [
        'required-user-login'
    ],
    function($request) {
        return new Response(200,Pages\Home::getHome($request));
    }
]);