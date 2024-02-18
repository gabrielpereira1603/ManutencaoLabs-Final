<?php

namespace app\Http;

class Request {

    /**
     * Instancia do router
     * @var router
     */
    private $router;
    /**
     * Metodo HTTP da requisicao
     *@var string
     */
    private $httpMethod;

    /**
     * URI da pagina
     * @var string
     */
    private $uri;

    /**
     * Parametros da URL($_GET)
     * @var array
     */
    private $queryParams = [];

    /**
     * Variaveis recebidas no post da pagina ($_POST)
     * @var array
     */
    private $postVars = [];

    /**
     * Cabecalho da requisicao
     * @var array
     */
    private $headers = [];
    /**
     * Construtor da classe
     */
    public function __construct($router) {
        $this->router = $router;
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER["REQUEST_METHOD"] ?? '';
        $this->setUri();
    }

    /**
     * Metodo responsavel por definir a URI
     */
    private function setUri(){
        //URI COMPRETA (COM GETS)
        $this->uri = $_SERVER["REQUEST_URI"] ?? '';

        //REMOVE GETS DA URI
        $xURI = explode('?',$this->uri);
        $this->uri = $xURI[0];
    }

    /**
     * Metodo responsavel por retornar a instancia de router
     * @return Router
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * METODO RESPONSAVEL POR RETORNA O METODO HTTP DA REQUISICAO
     * @return string
     */
    public function getHttpMethod() {
        return $this->httpMethod;
    }
    /**
     * METODO RESPONSAVEL POR RETORNA A URI REQUISICAO
     * @return string
     */
    public function getUri() {  
        return $this->uri;
    }
    /**
     * METODO RESPONSAVEL POR RETORNA Os HEADERS DA REQUISICAO
     * @return array
     */
    public function getHeaders() {
        return $this->headers;
    }
    /**
     * METODO RESPONSAVEL POR RETORNAR OS PARAMETROS DA URL DA REQUISICAO
     * @return array
     */
    public function getQueryParams() {  
        return $this->queryParams;
    }
    /**
     * METODO RESPONSAVEL POR RETORNA AS VARIAVEIS POST DA REQUISICAO
     * @return array
     */
    public function getPostVars() {
        return $this->postVars;
    }
}
