<?php

use \app\Http\Response;
use \app\Controller\Api;

// $obRouter->get('/api/v1/reclamacao',[
//     function($request) {
//         return new Response(200,Api\Reclamacao::getReclamacao($request), 'application/json');
//     }
// ]);

$obRouter->get('/api/v1/ComponenteReclamacao/{codreclamacao}',[
    function($request,$codreclamacao) {
        return new Response(200,Api\Componente::getComponenteReclamacao($request,$codreclamacao), 'application/json');
    }
]);

$obRouter->get('/api/v1/Componente',[
    function($request) {
        return new Response(200,Api\Componente::getComponente($request), 'application/json');
    }
]);
