<?php

namespace ABC\Abc\Core;

/** 
 * Класс BaseRequest
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
class BaseRequest
{
    /**
    * @var \ABC\Abc\Core\Router
    */
    public $router;
    
    public $GET;
    public $uriHash;
    
    /**
    * Конструктор
    *
    * @param object $router
    */    
    public function __construct($router)
    {
        $this->router = $router;
     
        if (!empty($_SERVER['QUERY_STRING'])) {
            $this->GET = $this->parseQueryString();
        } else {
            $this->GET = $this->parseRequestUri();
        }
    } 
    
    /**
    * Инициализация GET параметров
    *
    * @param string $key
    * @param string $default
    *
    * @return string
    */        
    public function iniGET($key, $default = null)
    {
        return isset($this->GET[$key]) ? $this->GET[$key] : $default;
    } 

    /**
    * Возвращает PATH
    *
    * @return srring
    */    
    public function getPath()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            return parse_url($_SERVER['REQUEST_URI'])['path'];        
        }    
        return '/';
    }
    
    /**
    * Разбирает в массив QUERY_STRING
    *
    * @return array
    */        
    protected function parseQueryString()
    {
        $queryString = urldecode($_SERVER['QUERY_STRING']);
        mb_parse_str($queryString, $out);
        return $out;
    }  

    /**
    * Разбирает массив HASH в массив GET по правилам роутинга
    *
    * @return void
    */    
    protected function parseRequestUri()
    {
        $uriHash = $this->createUriHash();
        return $this->router->convertUri($uriHash);
    }
    
    /**
    * Преобразует URI в массив HASH
    *
    * @return void
    */    
    protected function createUriHash()
    {
        $this->uriHash = explode('/', trim($this->getPath(), '/'));
        return $this->uriHash;
    }
}


