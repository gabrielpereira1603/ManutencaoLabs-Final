<?php

use \app\Http\Response;
use \app\Controller\Admin;


//ROTA LOGIN
$obRouter->get('/admin/manutencao/{codcomputador}',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$codcomputador) {
        return new Response(200,Admin\Manutencao::getManutencao($request,$codcomputador));
    }
]);

// //ROTA LOGIN(POST)
// $obRouter->post('/admin/login',[
//     'middlewares' => [
//         'required-admin-logout'
//     ],
//     function($request) {
//         return new Response(200,Admin\Login::setLogin($request));
//     }
    
// ]);

// //ROTA LOGOUT
// $obRouter->get('/admin/logout',[
//     'middlewares' => [
//         'required-admin-login'
//     ],
//     function($request) {
//         return new Response(200,Admin\Login::setLogout($request));
//     }
// ]);