<?php

namespace app\Controller\Pages;

use \app\Utils\View;  
use \app\Model\Entity\Laboratorio as EntityLaboratorio;
use \app\Model\Entity\Computador as EntityComputador;
use \app\Model\Entity\Laboratorio;

class Menu extends Page {

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
        $results = EntityLaboratorio::getLaboratorios(null, 'codlaboratorio');
        
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
                        $disponiveis++;
                        break;
                    case 2:
                        $indisponiveis++;
                        break;
                    case 3:
                        $emManutencao++;
                        break;
                    default:
                        // Situação inválida
                        break;
                }

                // Adicionar detalhes do computador ao HTML
                $computadoresHTML .= View::render('Pages/computador/item', [
                    'codcomputador' => $obComputador->codcomputador,
                    'codsituacao_fk' => $obComputador->codsituacao_fk
                ]);
            }

            // Adicionar detalhes do laboratório e computadores ao HTML final
            $itens .= View::render('Pages/laboratorio/item', [
                'codlaboratorio' => $obLaboratorio->codlaboratorio,
                'numerolaboratorio' => $obLaboratorio->numerolaboratorio,
                'computadores' => $computadoresHTML,
                'quantidade_disponiveis' => $disponiveis,
                'quantidade_indisponiveis' => $indisponiveis,
                'quantidade_em_manutencao' => $emManutencao,
                'quantidade_total_computadores' => $quantidadeTotalComputadores
            ]);
        }

        // Retornar os itens de laboratório
        return $itens;
    }

    /**
     * Metodo responsavel por renderizar a view de listagem de depoimentos
     * @param Request
     * @return string
     */
    public static function getMenu($request){
        //CONTEUDO DA HOME
        $content = View::render('Pages/menu',[
            'itens' => self::getLaboratorioItems($request)
        ]);

        //RETONA A PAGINA COMPLETA
        return parent::getPage('Menu', $content);
    }
}