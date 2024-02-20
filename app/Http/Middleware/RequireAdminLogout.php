<?php

namespace app\Http\Middleware;

use \app\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogout{

    /**
     * Metodo responsavel por executar o midleware
     * @param Request
     * @param Closure
     * @return Response
     */
    public function handle($request, $next){
        //VERIFICA SE O USUARIO ESTA LOGANDO
        if(SessionAdminLogin::isLogged()){
            $request->getRouter()->redirect('/admin');
        }

        //CONTINUA A EXECUCAO
        return $next($request);
    }
}