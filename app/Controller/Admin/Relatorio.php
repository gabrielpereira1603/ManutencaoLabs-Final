<?php

namespace app\Controller\Admin;
use Dompdf\Dompdf;
use Dompdf\Options;

use \app\Controller\Api;
use \app\Controller;
use \app\Model\Entity\User as EntityUser;
use \app\Model\Entity\Laboratorio as EntityLaboratorio;
use \app\Model\Entity\Manutencao as EntityManutencao;
use \app\Utils\View;

class Relatorio extends Page {
    public static function getRelatorio($request) {
        //CONTEUDO DA PAGINA DE RECLAMACAO
        $content = View::render('admin/modules/relatorio/index', [
 
        ]);
        
        //RETORNA A PAGINA COMPLETA
        return parent::getPanel('Relatório', $content, 'relatorio');
    }

    public static function getUsuarioItens($request) {
        $itens = '';
        $result = EntityUser::getAllUserAdmin();
        foreach ($result as $obUsuario) {
            $itens .= View::render('admin/usuarios/item', [
                'codusuario' => $obUsuario['codusuario'],
                'nome_usuario' => $obUsuario['nome_usuario'],
            ]);
        }
        return $itens;
    }

    public static function getLaboratorioItens($request) {
        $itens = '';
        $result = EntityLaboratorio::getLaboratorios();
        foreach ($result as $obLaboratorio) {
            $itens .= View::render('admin/laboratorio/select-item', [
                'numerolaboratorio' => $obLaboratorio['numerolaboratorio'],
                'codlaboratorio' => $obLaboratorio['codlaboratorio'],
            ]);
        }
        return $itens;
    }

    /**
     * Metodo responsavel por alimentar a tabela de ralatorio
     */
    public static function getRelatorioManutencaoItens($usuario,$laboratorio,$computador,$dataInicio,$dataFim) {
        $itens = '';
        $obManutencao = new EntityManutencao();
        $results = $obManutencao->gerarRelatorioManutencao($usuario,$laboratorio,$computador,$dataInicio,$dataFim);
        foreach ($results as $obManutencao) {
            $itens .= View::render('admin/modules/relatorio/manutencaoItem', [
                'datahora_manutencao' => isset($obManutencao['datahora_manutencao']) ? $obManutencao['datahora_manutencao'] : 'Nenhuma data encontrada',
                'nome_usuario_manutencao' => isset($obManutencao['nome_usuario_manutencao']) ? $obManutencao['nome_usuario_manutencao'] : 'Nenhum nome usuário encontrado',
                'descricao_manutencao' => isset($obManutencao['descricao_manutencao']) ? $obManutencao['descricao_manutencao'] : 'Nenhuma descrição encontrada',
                'status_reclamacao' => isset($obManutencao['status_reclamacao']) ? $obManutencao['status_reclamacao'] : 'Nenhum status de reclamação encontrado',
                'patrimonio' => isset($obManutencao['patrimonio']) ? $obManutencao['patrimonio'] : 'Nenhum patrimônio encontrado',
                'numerolaboratorio' => isset($obManutencao['numerolaboratorio']) ? $obManutencao['numerolaboratorio'] : 'Nenhum número de laboratório encontrado',
                'descricao_reclamacao' => isset($obManutencao['descricao_reclamacao']) ? $obManutencao['descricao_reclamacao'] : 'Nenhuma descrição de reclamação encontrada',
                'datahora_reclamacao' => isset($obManutencao['datahora_reclamacao']) ? $obManutencao['datahora_reclamacao'] : 'Nenhuma data e hora de reclamação encontrada',
                'componentes' => isset($obManutencao['componentes']) ? $obManutencao['componentes'] : 'Nenhum componente encontrado',
                'nome_usuario_reclamacao' => isset($obManutencao['nome_usuario_reclamacao']) ? $obManutencao['nome_usuario_reclamacao'] : 'Nenhum nome de usuário de reclamação encontrado',
                'login_reclamacao' => isset($obManutencao['login_reclamacao_reclamacao']) ? $obManutencao['login_reclamacao_reclamacao'] : 'Nenhum login de usuário de reclamação encontrado',
            ]);
        }
        return $itens;
    }

    /**
     * Metodo responsavel por rederizar a view de relatorio
     */
    public static function getRelatorioManutencao($request) {

        //CONTEUDO DA PAGINA DE RECLAMACAO
        $content = View::render('admin/modules/relatorio/manutencao', [
            'user' => self::getUsuarioItens($request),
            'laboratorio' => self::getLaboratorioItens($request),
        ]);
        
        //RETORNA A PAGINA COMPLETA
        return parent::getPanel('Relatório', $content, 'relatorio');
    }

    /**
     * Metodo responsavel por gerar o relatorio
     */
    public static function getRelatorioManutencaoTable($request) {
        // Obtém as informações da requisição
        $postVars = $request->getPostVars();
        $usuario = $postVars['usuario'] ?? '';
        $laboratorio = $postVars['laboratorio'] ?? '';
        $computador = $postVars['computador'] ?? '';
        $dataInicio = $postVars['dataInicio'] ?? '';
        $dataFim = $postVars['dataFim'] ?? '';

        $obManutencao = new EntityManutencao();
        $content = '';

        $content .= View::render('admin/modules/relatorio/manutencaoTable', [   
            'usuario' => $usuario,
            'laboratorio' => $laboratorio,
            'computador' => $computador,
            'dataInicio' => $dataInicio,
            'dataFim' => $dataFim,   
            'table-itens'  => self::getRelatorioManutencaoItens($usuario,$laboratorio,$computador,$dataInicio,$dataFim),
        ]);

        return parent::getPanel('Relatório', $content, 'relatorio');
    }

    public static function getDadosPDFManutencao($usuario,$laboratorio,$computador,$dataInicio,$dataFim) {
        $obManutencao = new EntityManutencao();
        $content = '';
            $content .= View::render('admin/modules/relatorio/PDFmanutencao', [
                'usuario' => $usuario,
                'laboratorio' => $laboratorio,
                'computador' => $computador,
                'dataInicio' => $dataInicio,
                'dataFim' => $dataFim,
                'itens' => self::getRelatorioManutencaoItens($usuario,$laboratorio,$computador,$dataInicio,$dataFim),
            ]);
        return $content;
    }

    public static function setRelatorioManutencao($request) {
        $postVars = $request->getPostVars();
        $usuario = $postVars['usuario'] ?? '';
        $laboratorio = $postVars['laboratorio'] ?? '';
        $computador = $postVars['computador'] ?? '';
        $dataInicio = $postVars['dataInicio'] ?? '';
        $dataFim = $postVars['dataFim'] ?? '';

        $ConteudoPDF = self::getDadosPDFManutencao($usuario,$laboratorio,$computador,$dataInicio,$dataFim);

        $options = new Options();
        $options->setDefaultFont('Courier');
        $options->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($ConteudoPDF);
        
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();
        
        // Output the generated PDF to Browser
        $dompdf->stream("realtorio.pdf");
    }
      
}
