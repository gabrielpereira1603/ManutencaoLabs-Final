<?php

namespace app\Controller\Admin;

use \app\Model\Entity\Computador as EntityComputador; 
use \app\Model\Entity\Laboratorio as EntityLaboratorio;
use \app\Model\Entity\Situacao as EntitySituacao;
use \app\Model\Entity\Reclamacao as EntityReclamacao;
use \app\Model\Entity\Manutencao as EntityManutencao;
use \app\Model\Entity\Foto as EntityFoto;
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
    
    public static function getFotoItens($codreclamacao) {
        // Fetch related photos from the database
        $results = EntityFoto::getFotoReclamacao($codreclamacao);

        if (is_array($results)) {
            $content = '';
            foreach ($results as $foto) {
                // Convert BLOB data to Base64
                $base64Image = base64_encode($foto['foto_reclamacao']);
        
                $content .= View::render('admin/foto/item', [
                    'foto_url' => $base64Image,
                ]);
            }
            
            return parent::getPage('Fotos da Reclamação', $content);
        } else {
            return parent::getPage('Fotos da Reclamação', 'Nenhuma foto encontrada para esta reclamação.');
        }
    }

    public static function getManutencao($request, $codcomputador) {

        $results = EntityReclamacao::getDetailsReclamacao($codcomputador);
        $obComputador = EntityComputador::getInfoComputador($codcomputador);
        $fotoBase64 = '';
        $colorStatus = '';

        //define a cor do background da view que mostra o status da reclamacao
        $colorStatus = ($obComputador->codsituacao_fk == 3) ? '#fde68a' : '#fca5a5';
        $content = '';
        if (empty($results)) {
            // Se $results estiver vazio, defina uma mensagem indicando que nenhuma reclamação foi encontrada
            $content = View::render('admin/modules/inserirManutencao/index', [
                'nav' => parent::getNav($request),
                'codreclamacao' => 'Nenhum ID Encontrado',
                'descricao'   => 'Nenhuma descrição Encontrada',
                'status'   => 'Nenhum Status Encontrado',
                'dataHora'   => 'Nenhuma Hora Encontrada',
                'numerolaboratorio' => 'Nenhum Laboratório Encontrado',
                'patrimonio' => 'Nenhum Patrimonio Encontrado',
                'nome_usuario' => 'Nenhum Usuário Encontrado',
                'email_usuario' => 'Nenhum Usuário Encontrado',
                'login' => 'Nenhum Usuário Encontrado',
                'componentes' => 'Nenhum Componente Encontrado',
                'foto'=> 'Nenhuma Foto Encontrado',
            ]);
        } else {
            // Se $results não estiver vazio, renderize as informações das reclamações encontradas
            foreach ($results as $obReclamacao) {
                $content .= View::render('admin/modules/inserirManutencao/index', [
                    'nav' => parent::getNav($request),
                    'codreclamacao' => isset($obReclamacao['codreclamacao']) ? $obReclamacao['codreclamacao'] : 'Nenhum ID Encontrado',
                    'descricao'   => isset($obReclamacao['descricao']) ? $obReclamacao['descricao'] : 'Nenhuma descrição Encontrada',
                    'status'   => isset($obReclamacao['status']) ? $obReclamacao['status'] : 'Nenhum Status Encontrado',
                    'dataHora'   => isset($obReclamacao['datahora_reclamacao']) ? $obReclamacao['datahora_reclamacao'] : 'Nenhuma Hora Encontrada',
                    'numerolaboratorio' => isset($obReclamacao['numerolaboratorio']) ? $obReclamacao['numerolaboratorio'] : 'Nenhum Laboratório Encontrado',
                    'patrimonio' => isset($obReclamacao['patrimonio']) ? $obReclamacao['patrimonio'] : 'Nenhum Patrimonio Encontrado',
                    'nome_usuario' => isset($obReclamacao['nome_usuario']) ? $obReclamacao['nome_usuario'] : 'Nenhum Usuário Encontrado',
                    'email_usuario' => isset($obReclamacao['email_usuario']) ? $obReclamacao['email_usuario'] : 'Nenhum Usuário Encontrado',
                    'login' => isset($obReclamacao['login']) ? $obReclamacao['login'] : 'Nenhum Usuário Encontrado',
                    'componentes' => self::getComponentesView($codcomputador),
                    'foto'=> self::getFotoItens($obReclamacao['codreclamacao']),
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
