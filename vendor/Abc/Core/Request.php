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
class Request
{
    /**
    * @var array
    */     
    protected $GET;
    
    /**
    * @var array
    */ 
    protected $uriHash;
    
    /**
    * @var array
    */ 
    protected $config; 
    
    /**
    * @param object $container
    */ 
    public function __construct($container)
    {
        $this->config = $container->get('config');
        $this->router = $container->get('Router');
     
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
    public function iniGET($key = null, $default = null)
    {
        if (empty($key)) {
            return @$this->GET;
        }
        
        return isset($this->GET[$key]) ? $this->GET[$key] : $default;
    } 
    

    /**
    * Возвращает HOST
    *
    * @return string
    */    
    public function getHostName()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        } elseif (isset($_SERVER['SERVER_NAME'])) {
            return $_SERVER['SERVER_NAME'];
        }
        
        return null;
    }

    /**
    * Возвращает PATH
    *
    * @return string
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
        mb_parse_str($queryString, $result);
        return $result;
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
        $uriHash = explode('/', trim($this->getPath(), '/'));
        
        if (!empty($this->config['url']['show_script'])) {
            array_shift($uriHash);
        }
        
        return $uriHash;
    }
}


