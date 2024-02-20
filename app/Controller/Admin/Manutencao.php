<?php

namespace app\Controller\Admin;

use \app\Model\Entity\Computador as EntityComputador; 
use \app\Model\Entity\Laboratorio as EntityLaboratorio;
use \app\Model\Entity\Situacao as EntitySituacao;
use \app\Model\Entity\Reclamacao as EntityReclamacao;
use \app\Utils\View;

class Manutencao extends Page {

    
    public static function getManutencao($request,$codcomputador) {

        $obReclamacao = EntityReclamacao::getDetailsReclamacao($codcomputador);
        $obComputador = EntityComputador::getInfoComputador($codcomputador);

        $content = View::render('admin/modules/inserirManutencao/index', [
            'nav' => parent::getNav($request),
            'codcomputador' => $codcomputador,
            'numerolaboratorio' => $obComputador->numerolaboratorio,
            'patrimonio' => $obComputador->patrimonio,
            'status' => isset($obReclamacao[0]['status']) ? $obReclamacao[0]['status'] : 'Nenhuma Reclamação Encontrado',
            'descricao'=> isset($obReclamacao[0]['descricao']) ? $obReclamacao[0]['descricao'] : 'Nenhuma Descrição Encontrado',
            'dataHora' => isset($obReclamacao[0]['datahora_reclamacao']) ? 
            date('H:i:s - d/m/Y', strtotime($obReclamacao[0]['datahora_reclamacao'])) : 'Nenhuma Data ou Horário Encontrado',
            'login' => isset($obReclamacao[0]['login']) ? $obReclamacao[0]['login'] : 'Nenhum Login Encontrado',
            'nome_usuario'=> isset($obReclamacao[0]['nome_usuario']) ? $obReclamacao[0]['nome_usuario'] : 'Nenhuma Descrição Encontrado',
            'email_usuario' => isset($obReclamacao[0]['email_usuario']) ? $obReclamacao[0]['email_usuario'] : 'Nenhum e-mail Encontrado',
        ]);
        
        //RETORNA A PAGINA COMPLETA
        return parent::getPage('Manutenção > Somos Devs',$content);
    }
}