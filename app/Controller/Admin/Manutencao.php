<?php

namespace app\Controller\Admin;

use \app\Model\Entity\Computador as EntityComputador; 
use \app\Model\Entity\Laboratorio as EntityLaboratorio;
use \app\Model\Entity\Situacao as EntitySituacao;
use \app\Model\Entity\Reclamacao as EntityReclamacao;
use \app\Model\Entity\Manutencao as EntityManutencao;
use \app\Utils\View;

class Manutencao extends Page {
    public static function getComponentesView($codcomputador) {
        $results = EntityReclamacao::getComponenteReclamacao($codcomputador);

        if (is_array($results)) {
            $content = '';
            foreach ($results as $obReclamacao) {
                $content .= View::render('admin/componente/item', [
                    'nome_componente'   => isset($obReclamacao['nome_componente']) ? $obReclamacao['nome_componente'] : 'Nenhum componente Encontrado',
                ]);
                
            }
            return parent::getPage('Manutenção', $content);
        }
    }
    

    public static function getManutencao($request, $codcomputador) {

        $results = EntityReclamacao::getDetailsReclamacao($codcomputador);
        $obComputador = EntityComputador::getInfoComputador($codcomputador);
        $fotoBase64 = '';
        $colorStatus = '';
    
        // Verificar se há uma imagem recuperada
        if (!empty($results[0]['foto_reclamacao'])) {
            // Convertendo a imagem para Base64
            $fotoBase64 = base64_encode($results[0]['foto_reclamacao']);
        }
        //define a cor do background da view que mostra o status da reclamacao
        $colorStatus = ($obComputador->codsituacao_fk == 3) ? '#fde68a' : '#fca5a5';
        $content = '';
        if (empty($results)) {
            // Se $results estiver vazio, defina uma mensagem indicando que nenhuma reclamação foi encontrada
            $content = View::render('admin/modules/inserirManutencao/index', [
                'nav' => parent::getNav($request),
                'codcomputador' => $codcomputador,
                'numerolaboratorio' => $obComputador->numerolaboratorio,
                'patrimonio' => $obComputador->patrimonio,
                'codreclamacao' => 'Nenhuma Reclamação Encontrada',
                'status' => 'Nenhuma Reclamação Encontrada',
                'descricao' => 'Nenhuma Descrição Encontrada',
                'dataHora' => 'Nenhuma Data ou Horário Encontrado',
                'login' => 'Nenhum Login Encontrado',
                'nome_usuario' => 'Nenhuma Descrição Encontrada',
                'email_usuario' => 'Nenhum e-mail Encontrado',
                'foto_base64' => $fotoBase64,
                'colorStatus' => $colorStatus,

            ]);
        } else {
            // Se $results não estiver vazio, renderize as informações das reclamações encontradas
            foreach ($results as $obReclamacao) {
                $content .= View::render('admin/modules/inserirManutencao/index', [
                    'nav' => parent::getNav($request),
                    // 'api' => self::getApi(),
                    'codcomputador' => $codcomputador,
                    'numerolaboratorio' => $obComputador->numerolaboratorio,
                    'patrimonio' => $obComputador->patrimonio,
                    'codreclamacao' => $obReclamacao['codreclamacao'],
                    'status' => $obReclamacao['status'],
                    'descricao' => $obReclamacao['descricao'],
                    'dataHora' => date('H:i:s - d/m/Y', strtotime($obReclamacao['datahora_reclamacao'])),
                    'login' => $obReclamacao['login'],
                    'nome_usuario' => $obReclamacao['nome_usuario'],
                    'email_usuario' => $obReclamacao['email_usuario'],
                    'foto_base64' => $fotoBase64,
                    'colorStatus' => $colorStatus,
                    'componentes' => self::getComponentesView($codcomputador),
                ]);
            }
        }
    
        //RETORNA A PAGINA COMPLETA
        return parent::getPage('Manutenção', $content);
    }
    

    public static function setManutencao($request,$codcomputador) {
        // Obtém os dados do usuário logado
        $userData = $_SESSION['admin']['usuario'];
        $codUsuario = $userData['codusuario'];

        // Obtém as informações da requisição
        $postVars = $request->getPostVars();
        $descricao = $postVars['descricao'] ?? '';
        $codreclamacao_fk = $postVars['codreclamacao'] ?? '';

        $obManutencao = new EntityManutencao();

        //DEFINE OS ATRIBUTOS DA MANUTENCAO
        $obManutencao->codusuario_fk = $codUsuario;
        $obManutencao->descricao_manutencao = $descricao;
        $obManutencao->codreclamacao_fk = $codreclamacao_fk;
        $obManutencao->cadastrarManutencao($codcomputador);

        if($obManutencao = true){
            $request->getRouter()->redirect('/admin?success=manutencaoAdd');
        }else{
            $request->getRouter()->redirect('/admin?success=manuntencaoNot');        
        }
    }
}            
