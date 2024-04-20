<?php

namespace app\Controller\Admin;

use \app\Controller\Api;
use \app\Controller;
use \app\Model\Entity\User as EntityUser;
use \app\Model\Entity\Laboratorio as EntityLaboratorio;
use \app\Model\Entity\Manutencao as EntityManutencao;
use \app\Utils\View;

class Gerenciar extends Page {
    public static function getGerenciar($request) {
        //CONTEUDO DA PAGINA DE RECLAMACAO
        $content = View::render('admin/modules/gerenciar/index', [
 
        ]);
        
        //RETORNA A PAGINA COMPLETA
        return parent::getPanel('Gerenciar', $content, 'Gerenciar');
    }
}