<?php

use \app\Http\Response;
use \app\Controller\Admin;

//ROTA Admin
$obRouter->get('/admin',[
    function($request) {
        return new Response(200,Admin\Menu::getMenu($request));
    }
]);


