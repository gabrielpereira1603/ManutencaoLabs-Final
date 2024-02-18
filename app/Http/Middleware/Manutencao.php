<?php

namespace app\Http\Middleware;

class Manutencao {

    /**
     * Metodo responsavel por executar o Middlewares
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request,$next){
        //VERIFICA O ESTADO DE MANUTENCAO DA PAGINA
        if(getenv('MANUTENCAO') == 'true'){
            throw new \Exception("Página em manutenção, Tente novamente mais tarde.", 200);
        }

        //EXECUTA O PROXIMO NIVEL DO MIDDLEWARE
        return $next($request);
    }

}