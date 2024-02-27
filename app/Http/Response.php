<?php

namespace app\Http;

class Response {
    
    /**
     * Codigo do status HTTP
     * @var integer
     */
    private $httpCode = 200;

    /**
     * Cabecalho do Response
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de conteudo que esta sendo retornado
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Conteu do Response
     * @var mixed
     */
    private $content;
    /**
     * @param integer
     * @param mixed
     * @param string
     */
    public function __construct($httpCode, $content, $contentType = 'text/html') {
        $this->httpCode = $httpCode;    
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * Metodo responsavel por alterar o contentType do response
     * @param string
     */
    public function setContentType($contentType) {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Metodo responsavel por adicionar um registro no cabecalho do response
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value) {
        $this->headers[$key] = $value;
    }
    /**
     * Metodo responsavel por enviar os headers para o navegador
     */
    private function sendHeaders() {
        //STATUS
        http_response_code($this->httpCode);

        //ENVIAR HEADERS
        foreach($this->headers as $key=>$value) {
            header($key .': '. $value);
        }
    }
    /**
     * metodo responsavel por enviar a resposta para o usuario
     */
    public function sendResponse() {
        //ENVIA OS HEADERS
        $this->sendHeaders();

        //IMPRIME O CONTEUDO 
        switch ($this->contentType){
            case 'text/html':
                echo$this->content;
                exit;
            case 'application/json':
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
        }
    }
}