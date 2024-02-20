<?php

namespace app\Controller\Pages;

use \app\Utils\View;

class Page {
    
    /**
     * Modulos disponiveis no painel
    */
    private static $modules = [
        'home' =>[
            'label' => 'Home',
            'link' => URL.'/'
        ],
        'reclamacoesAbertas' =>[
            'label' => 'Reclamações Abertas',
            'link' => URL.'/raclamacoesAbertas'
        ],
        'historicoReclamacao' =>[
            'label' => 'Histórico Reclamações',
            'link' => URL.'/historicoReclamacao'
        ],
        'termosDeUso' => [
            'label' => 'Termos De Uso',
            'link'=> URL.'/regras'
        ],
        'configuracao'=> [
            'label'=> 'Settings',
            'link'=> URL.'/settingsUser'
        ],
    ];

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
            'content' => $content,
            'footer' => self::getFooter()
        ]);
  
    }

    /**
     * Metodo responsavel por renderizar a view do header de navegacao
     * @param string
     * return string
    */
    public static function getNav($currentModule){
        //LIKS DO MENU
        $links = '';

        // Itera os módulos
        foreach(self::$modules as $hash => $module){
            // Adiciona o ícone correspondente com base no hash do módulo
            $iconClass = '';
            switch ($hash) {
                case 'home':
                    $iconClass = 'bx bx-home';
                    break;
                case 'reclamacoesAbertas':
                    $iconClass = 'bx bx-comment-edit';
                    break;
                case 'historicoReclamacao':
                    $iconClass = 'bx bx-file';
                    break;
                case 'termosDeUso':
                    $iconClass = 'bx bx-book';
                    break;
                case 'configuracao':
                    $iconClass = 'bx bx-cog';
                    break;
                // Adicione mais casos para outros módulos conforme necessário
            }

            // Renderiza o link com o ícone correspondente
            $links .= View::render('Pages/menu/link',[
                'label' => $module['label'],
                'link' =>  $module['link'],
                'iconClass' => $iconClass,
                'current' => $hash == $currentModule ? 'active' : '' /**deixa o link que voce estiver vermelho */
            ]);
        }

        
        //RETORNA A RENDERIZACAO DO MENU
        return View::render('Pages/menu/nav',[
            'links' => $links
        ]);
    }

    /**
     * Metodo responsavel por renderizar a view do painel com conteudos dinamicos
     * @param string $titlre
     * @param string $content
     * @param string $currentModule
     * @return string
    */
    public static function getPanel($title,$content,$currentModule){
        //RENDERIZA A VIEW DO PAINEL
        $contentPanel = View::render('Pages/panel',[
            'menu' => self::getNav($currentModule),
            'content'=> $content,
        ]);

        //RETORNA A PAGINA RENDERIZADA
        return self::getPage($title,$contentPanel);
    }

    /**
     * Metodo responsavel por renderizar o layout de paginacao
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    public static function getPagination($request,$obPagination) {
        //PAGINAS
        $pages = $obPagination->getPages();

        //VERIFICA A QUANTIDADE DE PAGINAS
        if(count($pages) <= 1) return '';

        //LINKS
        $links = '';

        //URL ATUAL (SEM GETS)
        $url = $request->getRouter()->getCurrentUrl();

        //GET
        $queryParams = $request->getQueryParams();
        //RENDERIZA OS LINKS
        foreach($pages as $page) {
            //ALTERA A PAGINA
            $queryParams['page'] = $page['page'];
            
            //LINK
            $link = $url.'?'.http_build_query($queryParams);

            //VIEW
            $links .= View::render('Pages/pagination/link', [
                'page' => $page['page'],
                'link' => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
        }
        
        //RENDERIZA BOX DE PAGINACAO
        return View::render('Pages/pagination/box', [
            'links' => $links
        ]);
    }
}