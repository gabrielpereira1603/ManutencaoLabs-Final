<?php

use \app\Http\Response;
use \app\Controller\Pages;


//ROTA LOGIN
$obRouter->get('/login',[
    'middlewares' => [
        'required-user-logout'
    ],
    function($request) {
        return new Response(200,Pages\Login::getLogin($request));
    }
]);

//ROTA LOGIN(POST)
$obRouter->post('/login',[
    'middlewares' => [
        'required-user-logout'
    ],
    function($request) {
        return new Response(200,Pages\Login::setLogin($request));
    }
    
]);

//ROTA LOGOUT
$obRouter->get('/logout',[
    'middlewares' => [
        'required-user-login'
    ],
    function($request) {
        return new Response(200,Pages\Login::setLogout($request));
    }
]);