<?php
namespace app\Controller\Admin;

use \app\Utils\View;

class Page {

    /**
     * Modulos disponiveis no painel
    */
    private static $modules = [
        'home' =>[
            'label' => 'Home',
            'link' => URL.'/admin'
        ],
        'user' =>[
            'label' => 'User',
            'link' => URL.'/admin/user'
        ],
        'dashboard' =>[
            'label' => 'Dashboard',
            'link' => URL.'/admin/dashboard'
        ],
        'relatorio'=> [
            'label'=> 'Relatório',
            'link'=> URL.'/admin/relatorio'
        ],
        'termosDeUso' => [
            'label' => 'Termos De Uso',
            'link'=> URL.'/regras'
        ],
        'configuracao'=> [
            'label'=> 'Settings',
            'link'=> URL.'/settings'
        ],
    ];

    /**
     * Metodo responsavel por retornar o conteudo (view) da estrutura generica de pagina do painel
     * @param string
     * @param string
     * @return string
     */
    public static function getPage($title, $content){
        return View::render('admin/page', 
        [
            'title' => $title,
            // 'header' => self::getHeader(),
            'content' => $content
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
                case 'user':
                    $iconClass = 'bx bx-user';
                    break;
                case 'dashboard':
                    $iconClass = 'bx bxs-dashboard';
                    break;
                case 'termosDeUso':
                    $iconClass = 'bx bx-book';
                    break;
                case 'configuracao':
                    $iconClass = 'bx bx-cog';
                    break;
                case 'relatorio':
                    $iconClass = 'bx bx-file';
                    break;
                // Adicione mais casos para outros módulos conforme necessário
            }

            // Renderiza o link com o ícone correspondente
            $links .= View::render('admin/menu/link',[
                'label' => $module['label'],
                'link' =>  $module['link'],
                'iconClass' => $iconClass,
                'current' => $hash == $currentModule ? 'active' : '' /**deixa o link que voce estiver vermelho */
            ]);
        }

        
        //RETORNA A RENDERIZACAO DO MENU
        return View::render('admin/menu/nav',[
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
        $contentPanel = View::render('admin/panel',[
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
            $links .= View::render('admin/pagination/link', [
                'page' => $page['page'],
                'link' => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
        }
        
        //RENDERIZA BOX DE PAGINACAO
        return View::render('admin/pagination/box', [
            'links' => $links
        ]);
    }

}