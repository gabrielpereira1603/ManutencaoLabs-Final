<?php

use \app\Http\Response;
use \app\Controller\Api;

$obRouter->get('/api/v1/user',[
    function($request) {
        return new Response(200,Api\User::getUsers($request), 'application/json');
    }
]);

$obRouter->get('/api/v1/user/{codusuario}', [
    'middleware' => [
        'api'
    ],
    function($request, $codusuario) {
        // Chama o método na sua API ou na sua lógica de negócio
        return new Response(200, Api\User::getUserByID($request, $codusuario), 'application/json');
    }
]);