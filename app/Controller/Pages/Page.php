<?php

namespace app\Controller\Pages;

use \app\Utils\View;

class Page {

    /**
     * Metodo responsavel por renderizar o topo da pagina
     * caminho do header: estudo-mvc\resources\Pages\header.html
     * @return string
     */
    private static function getHeader() {
        return View::render('Pages/header');
    }

    /**
     * Metodo responsavel por renderizar o footer da pagina
     * caminho do footer: estudo-mvc\resources\Pages\footer.html
     * @return string
     */
    private static function getFooter() {
        return View::render('Pages/footer');
    }

    /**
     * Metodo responsavel por retorna o conteudo da (view) da nossa pagina generica
     */
    public static function getPage($title, $content){
        return View::render('Pages/page', 
        [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
            
        ]);
  
    }

    
}