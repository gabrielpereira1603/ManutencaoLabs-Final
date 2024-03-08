<?php

namespace app\Controller\Pages;

use \app\Utils\View;  
use \app\Model\Entity\Laboratorio as EntityLaboratorio;
use \app\Model\Entity\Computador as EntityComputador;

class Home extends Page {
    
    /**
     * Metodo responsavel por obter a rendericacao dos itens de depoimento para a pagina
     * @param Resquest $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getLaboratorioItems($request){
        //DEPOIMENTOS
        $itens = '';

        //RESULTADOS DA PAGINA
        $results = EntityLaboratorio::getLaboratorios();
        
        // Dentro do loop while que itera sobre os laboratórios
        while ($obLaboratorio = $results->fetchObject(EntityLaboratorio::class)) {
            $codLaboratorio = $obLaboratorio->codlaboratorio;

            // Obter os computadores para o laboratório atual
            $computadores = EntityComputador::getComputadoresLaboratorio($codLaboratorio);

            // Contador para a quantidade total de computadores no laboratório atual
            $quantidadeTotalComputadores = $computadores->rowCount();

            // Iniciar a renderização dos computadores para este laboratório
            $computadoresHTML = '';

            // Contadores para cada tipo de situação
            $disponiveis = 0;
            $indisponiveis = 0;
            $emManutencao = 0;

            // Iterar sobre os computadores deste laboratório
            while ($obComputador = $computadores->fetchObject(EntityComputador::class)) {
                // Atualizar os contadores com base no tipo de situação do computador
                switch ($obComputador->codsituacao_fk) {
                    case 1:
                        $emManutencao++;
                        break;
                    case 2:
                        $disponiveis++;
                        break;
                    case 3:
                        $indisponiveis++;
                        break;
                    default:
                        break;
                }
            }

            // Adicionar detalhes do laboratório e computadores ao HTML final (GERA OS CARDS)
            $itens .= View::render('Pages/laboratorio/item', [
                'codlaboratorio' => $obLaboratorio->codlaboratorio,
                'numerolaboratorio' => $obLaboratorio->numerolaboratorio,
                'quantidade_disponiveis' => $disponiveis,
                'quantidade_indisponiveis' => $indisponiveis,
                'quantidade_em_manutencao' => $emManutencao,
                'quantidade_total_computadores' => $quantidadeTotalComputadores
            ]);
        }

        // Retornar os itens de laboratório
        return $itens;
    }

    public static function getHome($request){
        //CONTEUDO DA HOME
        $content = View::render('Pages/modules/home/index',[
            'nav' => parent::getNav($request),
            'itens' => self::getLaboratorioItems($request)
        ]);

        //RETONA A PAGINA COMPLETA
        return parent::getPage('Home', $content, 'home');
    }
}