<?php

use \app\Http\Response;
use \app\Controller\Admin;

//ROTA Admin
$obRouter->get('/admin/user',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200,Admin\User::getUser($request));
    }
]);

//ROTA ADD USER
$obRouter->get('/admin/user/add',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200,Admin\User::getNewUser($request));
    }
]);

//ROTA ADD USER(post)
$obRouter->post('/admin/user/add',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200,Admin\User::setNewUser($request));
    }
]);


//ROTA ALTERAR ACESSO(get)
$obRouter->get('/admin/user/acesso',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200,Admin\User::getAcesso($request));
    }
]);

//ROTA ALTERAR ACESSO(post)
$obRouter->post('/admin/user/acesso',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200,Admin\User::setAcesso($request));
    }
]);

//ROTA ALTERAR INFORMACOES(get)
$obRouter->get('/admin/user/update',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200,Admin\User::getUpdate($request));
    }
]);

//ROTA ALTERAR INFORMACOES(post)
$obRouter->post('/admin/user/update',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200,Admin\User::setUpdate($request));
    }
]);

