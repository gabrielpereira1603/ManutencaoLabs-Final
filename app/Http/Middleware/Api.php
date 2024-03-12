<?php

namespace app\Http\Middleware;

class Api {

    /**
     * Metodo responsavel por executar o Middlewares
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request,$next){
        //ALTERA O CONTENT TYPE PARA JSON
        $request->getRouter()->setContentType('application/json ');

        //EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
        return $next($request);
    }

}