<?php

use \app\Http\Response;
use \app\Controller\Api;

$obRouter->get('/api/v1/computadores',[
    function($request) {
        return new Response(200,Api\Computador::getComputadores($request), 'application/json');
    }
]);