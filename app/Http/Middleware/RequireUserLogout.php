<?php

namespace app\Http\Middleware;

use \app\Session\User\Login as SessionUserLogin;

class RequireUserLogout{

    /**
     * Metodo responsavel por executar o midleware
     * @param Request
     * @param Closure
     * @return Response
     */
    public function handle($request, $next){
        //VERIFICA SE O USUARIO ESTA LOGANDO
        if(SessionUserLogin::isLogged()){
            $request->getRouter()->redirect('/');
        }

        //CONTINUA A EXECUCAO
        return $next($request);
    }
}