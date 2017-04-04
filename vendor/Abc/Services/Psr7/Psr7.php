<?php

namespace ABC\Abc\Services\Psr7;

use ABC\Abc\Core\Exception\AbcError;

/** 
 * Класс Psr7 
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author irbis-team.ru
 * @copyright © 2017
 * @license http://www.wtfpl.net/ 
 */   
class Psr7
{   
    protected $abc;

    /**
    * Конструктор.
    *
    * @param object $abc.
    */
    public function __construct($abc)
    {
        $this->abc = $abc;
    }

    /**
    * Возвращает дефолтный Request.
    *
    * @return object.
    */
    public function getRequest()
    {
        return new Request($this->abc);
    }
    
    /**
    * Возвращает дефолтный Response.
    *
    * @return object.
    */
    public function getResponse()
    {
        return new Response($this->abc);
    }
    
    /**
    * Инициализирует и возвращает класс Request.
    *
    * @return object.
    */
    public function createRequest($method        = null, 
                                  $uri           = null, 
                            array $headers       = null, 
                            array $cookies       = null,
                            array $serverParams  = null,
                                  $body          = null, 
                            array $uploadedFiles = []
    ) {
        return (new Request($this->abc))->newRequest($method, 
                                                     $uri, 
                                                     $headers, 
                                                     $cookies, 
                                                     $serverParams, 
                                                     $body, 
                                                     $uploadedFiles
        );
    }
    
    /**
    * Инициализирует и возвращает класс Response.
    *
    * @return object.
    */
    public function createResponse($body = 'php://memory', 
                                   $status = 200, 
                             array $headers = []
    ) {
        return new Response($this->abc, $body, $status, $headers);
    }
    
    /**
    * Инициализирует и возвращает класс Uri.
    *
    * @return object.
    */
    public function createUri($uri = '')
    {
        return new Uri($this->abc, $uri);
    }    

    /**
    * Инициализирует и возвращает класс Stream.
    *
    * @return object.
    */
    public function createStream($stream, $mode = 'r')
    {
        return new Stream($stream, $mode);
    }    
    
    /**
    * Инициализирует и возвращает класс UploadedFile.
    *
    * @return object.
    */
    public function createUploadedFile($file = null, 
                                       $name = null, 
                                       $type = null, 
                                       $size = null, 
                                       $error = UPLOAD_ERR_OK
    ) {
        return new UploadedFile($file, $name, $type, $size, $error);
    }
}
