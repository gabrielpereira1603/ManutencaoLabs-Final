<?php

use \app\Http\Response;
use \app\Controller\Admin;

//ROTA DE RELATORIO
$obRouter->get('/admin/relatorio',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200,Admin\Relatorio::getRelatorio($request));
    }
]);

//ROTA DE RELATORIO de manutencao
$obRouter->get('/admin/relatorio/manutencao',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200,Admin\Relatorio::getRelatorioManutencao($request));
    }
]);

//ROTA DE RELATORIO de manutencao
$obRouter->post('/admin/relatorio/manutencao',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200,Admin\Relatorio::getRelatorioManutencaoTable($request));
    }
]);

//ROTA DE RELATORIO de manutencao
$obRouter->post('/admin/relatorio/PDF',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200,Admin\Relatorio::setRelatorioManutencao($request));
    }
]);