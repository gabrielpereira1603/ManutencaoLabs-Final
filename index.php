<?php

require __DIR__ .'/includes/app.php';

use \app\Http\Router;

$obRouter = new Router(URL);

//INCLUI AS ROTAS DE PAGINAS
include __DIR__.'/routes/pages.php';

//INCLUI AS ROTAS DE ADMIN
include __DIR__.'/routes/admin.php';

//INCLUI AS ROTAS DE API
include __DIR__.'/routes/api.php';

//IMPRIMI O RESPONSE DA ROTA
$obRouter->run()->sendResponse();
