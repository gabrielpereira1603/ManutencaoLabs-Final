<?php

require __DIR__ ."/../vendor/autoload.php";

use \app\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use \WilliamCosta\DatabaseManager\Database;
use \app\Http\Middleware\Queue as MiddlewareQueue;

//CARREGA VARIAVEIS DE AMBIENTE 
Environment::load(__DIR__.'/../');

//DEFINE AS CONFIGURACOES DE BANCO DE DADOS
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);

//DEFINE A CONSTANTE DE URL DO PROJETO
define('URL', getenv('URL'));

//DEFINE O VALOR PADRAO DAS VARIAVEIS
View::init([
    'URL' => URL
]);

