<?php

namespace app\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \app\Http\Middleware\Queue as MiddlewareQueue;

class Router {
    /**
     * URL completa do projeto(raiz)
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Indice de rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instacia de Request
     * @var Request
     */
    private $request;

    /**
     * Retorna o Content type
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Metodo responsavel por iniciar a classe
     * @param string $url
     */
    public function __construct($url) {
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * Metodo responsvel por alterar o contenttype
     */
    public function setContentType($contentType) {
        $this->contentType = $contentType;
    }

    /**
     * Metodo responsavel por definir o prefixo das rotas
     */
    private function setPrefix() {
        //INFORMACOES DA URL ATUAL
        $parseUrl = parse_url($this->url);
 
        //DEFINE O PREFIXO
        $this->prefix = $parseUrl['path'] ?? '';
    }
    
    /**
     * Metodo responsável por adicionar uma rota na classe
     * @param string $method
     * @param string $route
     * @param array  $params
     */
    private function addRout($method,$route,$params = []) {
        //VALIDACAO DOS PARAMETROS
        foreach($params as $key=>$value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //MIDDLEWARES DA ROTA
        $params['middlewares'] = $params['middlewares'] ?? [];

        //VARIVEIS DA ROTA
        $params['variables'] = [];

        //PADRAO DE VALIDACAO DAS VARIAVEIS DAS ROTAS
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable,$route,$matches)) {
            $route = preg_replace($patternVariable, '(.*?)',$route);
            $params['variables'] = $matches[1];
        }
        
        //PADRAO DE VALIDACAO DA URL
        $patternRoute = '/^'.str_replace('/','\/',$route).'$/';
        //ADICIONA A ROTA DENTRO DA CLASSA
        $this->routes[$patternRoute][$method] = $params;
      
    }

    /**
     * Metodo responsavel por definir um rota de GET
     * @param string
     * @param array
     */
    public function get($route,$params = []) {
        return $this->addRout('GET', $route, $params);
    }

    
    /**
     * Metodo responsavel por definir um rota de post
     * @param string
     * @param array
     */
    public function post($route, $params = []) {
        return $this->addRout('POST', $route, $params);
    }

    /**
     * Metodo responsavel por definir um rota de PUT
     * @param string
     * @param array
     */
    public function put($route, $params = []) {
        return $this->addRout('PUT', $route, $params);
    }

    /**
     * Metodo responsavel por definir um rota de delete
     * @param string
     * @param array
     */
    public function delete($route, $params = []) {
        return $this->addRout('DELETE', $route, $params);
    }


    /**
     * Metodo responsavel por retorna a URI desconsiderando o prefixo
     * @return string
     */
    private function getUri() {
        //URI DA REQUEST
        $uri = $this->request->getUri();    

        //FATIA O URI COM O PREFIXO
        $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri];

        //RETORNA A URI SEM O PREFIXO
        return end($xUri);
    }

    /**
     * Metodo responsavel por retornar os dados da rota atual
     * @return array
     */
    private function getRoute() {
        //URI 
        $uri = $this->getUri();

        //METHOD
        $httpMethod = $this->request->getHttpMethod();

        
        //VALIDA AS ROTAS
        foreach($this->routes as $patternRoute=>$methods) {
            //VERIFICA SE A URI BATE O PADRAO
            if(preg_match($patternRoute,$uri,$matches)){
                //VERIFICA O METODO
                if(isset($methods[$httpMethod])) {
                    //REMOVE A PRIMEIRA POSICAO
                    unset($matches[0]);

                    //VARIAVEIS PROCESSADAS
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys,$matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    
                    //RETORNO DOS PARAMETROS DAS ROTAS
                    return $methods[$httpMethod];  
                }
                //METODO NAO PERMITIDO
                throw new Exception("Método não permitido", 405);
            }
        }

        //URL NAO ENCONTRADA
        throw new Exception("URL não encontrada",404);
    }

    /**
     * Metodo responsavel por executar a rota atual
     * @return Response
     */
    public function run() {
        try {
            //OBTEM A ROTA ATUAL
            $route = $this->getRoute();

            //VERIFICA O CONTROLADOR
            if(!isset($route['controller'])) {
                throw new Exception("URL não pode pôde ser processada", 500);
            }
            //ARGUMENTOS DA FUNCAO
            $args = [];

            //REFLECTION
            $reflection = new ReflectionFunction($route['controller']);
            foreach($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();  
                $args[$name] = $route['variables'][$name] ?? null;
            }


            //RETORNA A EXECUCAO DA FILA DE MIDDLEWARES
            return (new MiddlewareQueue($route['middlewares'],$route['controller'],$args))->next($this->request);
        }catch(Exception $e){
            return new Response($e->getCode(),$this->getErrorMessage($e->getMessage()),$this->contentType);   
        }
    }
    /**
     * Metodo responsavel por retornar a mensagemd de error de acordo com content type
     * @param string
     * @return mixed
     */
    private function getErrorMessage($message) {
        switch($this->contentType) {
            case 'application/json':
            return [
                'error' => $message
            ];
            break;

            default:
            return $message;
            break;
        }
    }

    /**
     * Metodo reponsavel por retorna a URL atual
     * @return string
     */
    public function getCurrentUrl() {
        return $this->url.$this->getUri();
    }
    
    

    /**
     * Metodo responsavel por redirecionar a URL
     * @param string
     */
    public function redirect($route){
        //URL
        $url = $this->url.$route;
        
        //EXECURA O REDIRECT
        header('location: '.$url);
        exit;
    }
}