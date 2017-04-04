<?php

namespace ABC\Abc\Core;

use ABC\Abc\Core\Exception\AbcError;

/** 
 * Класс Request
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/
 */   
class Request
{

    protected $GET;
    
        
    /**
    * Конструктор
    */ 
    public function __construct($abc = null)
    {
        if (!empty($abc)) {
            if (!empty($_SERVER['QUERY_STRING'])) {
                $this->GET = $this->parseQueryString();
            } else {
                $path = $this->getPath();
                $router  = $abc->sharedService('Router');            
                $this->GET = $router->createGet($path);
            } 
        }
    }
 
    /**
    * Инициализация GET параметров
    *
    * @param string $key
    * @param string $default
    *
    * @return string|array
    */        
    public function get($key = null, $default = null)
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
    * @return string|array
    */        
    public function post($key = null, $default = null)
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
    public function cookie($key = null, $default = null)
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
}
