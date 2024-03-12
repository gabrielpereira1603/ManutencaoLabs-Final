<?php

use \app\Http\Response;
use \app\Controller\Api;

// $obRouter->get('/api/v1/reclamacao',[
//     function($request) {
//         return new Response(200,Api\Reclamacao::getReclamacao($request), 'application/json');
//     }
// ]);

$obRouter->get('/api/v1/reclamacao',[
    function($request) {
        return new Response(200,Api\Reclamacao::getReclamacaoPorLab($request), 'application/json');
    }
]);