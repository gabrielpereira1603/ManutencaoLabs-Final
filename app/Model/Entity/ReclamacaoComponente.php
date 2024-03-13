<?php

namespace app\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class ReclamacaoComponente {
    public $codreclamacao_fk;
    public $codcomponente_fk;

    public static function setReclamacaoComponente($componente,$codreclamacao) {
        // Verifica se $componente não está vazio e se é uma string
        if (!empty($componente) && is_string($componente)) {
            // Converte a string em um array separando os elementos pela vírgula
            $componente = explode(',', $componente);
        }

        // Agora $componente deve ser um array ou vazio, então podemos continuar
        if (is_array($componente)) {
            foreach ($componente as $codComponente) {
                $database = new Database('reclamacao_componente');
                $database->insert([
                    'codreclamacao_fk' => $codreclamacao, 
                    'codcomponente_fk' => $codComponente
                ]);
            }
            return true;
        } else {
            // Trate o caso em que $componente não é um array válido
            // Por exemplo, você pode lançar uma exceção ou retornar false
            return false;
        }
    }


}
