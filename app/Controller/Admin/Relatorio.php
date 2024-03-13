<?php

namespace app\Controller\Admin;

use \app\Controller\Api;
use \app\Utils\View;

class Relatorio extends Page {
    public static function getRelatorio($request) {
        //CONTEUDO DA PAGINA DE RECLAMACAO
        $content = View::render('admin/modules/relatorio/index', [
 
        ]);
        
        //RETORNA A PAGINA COMPLETA
        return parent::getPanel('Relatório', $content, 'relatorio');
    }
}