<?php

namespace app\Utils;

class View {

    /**
     * Variaveis padroes da View
     * @var array
     */
    private static $vars = [];

    /**
     * Metodo responsavel por definir os dados inicias da classe
     * @param array $vars
     */
    public static function init($vars = []) {
        self::$vars = $vars;
    }

    // Método para retornar o conteúdo de uma view
    // @param string $view
    // @return string
    private static function getConteudoView($view){
        $local1 = __DIR__ .'/../../resources/view/'.$view.'.html';
        $local2 = '/home2/somos411/public_html/SMI/resources/View/'.$view.'.html';

        if (file_exists($local1)) {
            return file_get_contents($local1);
        } elseif (file_exists($local2)) {
            return file_get_contents($local2);
        } else {
            return '';
        }
    }

    //metodo para retornar o conteudo de uma view
    //@param string view
    //@return string
    // private static function getConteudoView($view){
    //     $file = __DIR__ .'/../../resources/view/'.$view.'.html';
    //     return file_exists($file) ? file_get_contents($file) :'';
    // }

    // private static function getConteudoView($view){
    //     $file = '/home2/somos411/public_html/SMI/resources/View/'.$view.'.html';
    //     return file_exists($file) ? file_get_contents($file) :'';
    // }

    //Metodo responsavel por retorna o conteudo renderizado da view
    //@param string view
    //@param string/numeric
    //@return string
    public static function render($view, $vars = []) {
        //CONTEUDO DA VIEW
        $conteudoView = self::getConteudoView($view);


        //MERGE DE VARIAVEIS DA VIEW
        $vars = array_merge(self::$vars, $vars);

        //CHAVES DO ARRAY DE VARIAVEIS
        $keys = array_keys($vars);
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        }, $keys);

        //RETORNA O CONTEUDO RENDERIZADO
        return str_replace($keys,array_values($vars), $conteudoView);
    }
}