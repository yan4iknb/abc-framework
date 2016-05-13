<?php

namespace ABC\Abc\Core;

/** 
 * Класс Request
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
class Request
{
   
    protected $GET;
    protected $uriHash;
    protected $config; 
    protected $router;
    
    /**
    * @param object $abc
    */ 
    public function __construct($abc)
    {
        $this->config = $abc->getFromStorage('config');
        $this->router = $abc->getFromStorage('Router');
     
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
    * Инициализация POST параметров 
    *
    * @param string $key
    * @param string $default
    *
    * @return string
    */        
    public function iniPOST($key = null, $default = null)
    {
        if (empty($key)) {
            return @$_POST;
        }
        
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }   
    
    /**
    * Инициализация параметров COOKIE
    *
    * @param string $key
    * @param string $default
    *
    * @return string
    */        
    public function iniCOOKIE($key = null, $default = null)
    {
        if (empty($key)) {
            return @$_COOKIE;
        }
        
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default;
    }
    
    /**
    * Возвращает текущий контроллер
    *
    * @return string
    */    
    public function getController()
    {
        $get = $this->GET;
        return array_shift($get);
    }
    
    /**
    * Возвращает текущий экшен
    *
    * @return string
    */    
    public function getAction()
    {    
        $get = $this->GET;
        array_shift($get);    
        return array_shift($get);
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
    * Возвращает текущий протокол
    *
    * @return string
    */ 
    public function getProtocol()
    {
        return (isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1)
               || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0) 
                ? 'https' : 'http';
    }
    
    /**
    * Возвращает базовый URL
    *
    * @return string
    */    
    public function getBaseUrl()
    {

        return $this->getProtocol() .'://'. $this->getHostName();
    }
    
    /**
    * Проверяет, отправлен запрос AJAX'ом или нет
    *
    * @return bool
    */  
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
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
    

    /**
    * Разбирает массив HASH в массив GET по правилам роутинга
    *
    * @return void
    */    
    protected function parseRequestUri()
    {
        $uriHash = $this->createUriHash();
        $string  = $this->getPath();
        return $this->router->convertUri($uriHash, $string);
    }
}


