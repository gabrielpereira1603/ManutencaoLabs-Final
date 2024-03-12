<?php

namespace app\Controller\Admin;

use \app\Model\Entity\Computador as EntityComputador; 
use \app\Model\Entity\Laboratorio as EntityLaboratorio;
use \app\Model\Entity\Situacao as EntitySituacao;
use app\Controller\Api\Computador as ApiComputador;
use \app\Utils\View;

class Dashboard extends Page{ 
    public static function getDashboard($request) {
        $content = View::render('admin/modules/dashboard/index', [

        ]);

        return parent::getPanel('Dashboard', $content, 'dashboard');
    }
}