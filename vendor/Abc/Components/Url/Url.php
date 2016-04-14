<?php 

namespace ABC\Abc\Components\Url;

use ABC\Abc;

/** 
 * Класс Url
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */ 
   
class Url  
{  
    public $config;
    public $request;
    public $router;
    
    public function __construct($request)
    {
        $this->request = $request;
        $this->router  = $request->router;
    }
        
    /**
    * Получаем URL согласно роутам
    *
    * @param string $string
    * @param bool|array $mode
    *
    * @return string
    */     
    public function getUrl($string, $mode = false)
    {
        $param = $this->router->hashFromUrl($string); 
        return $this->createUrl($param, $mode);
    }
    
    /**
    * Добавляет параметры в URL
    *
    * @param string $string
    *
    * @return string
    */     
    public function addParamToUrl($string)
    {
        $get = Abc::GET();
        $addition = $this->router->createGetFrom($string);
        $param = array_merge($get, $addition);
        return $this->createUrl($param, $abs = false);
    }
    
    /**
    * Преобразует строку URL в массив согласно роутам
    *
    * @param string $string
    *
    * @return array
    */     
    public function createGetFrom($string)
    {
        return $this->router->createGetFrom($string);
    }
    
    /**
    * Получает массив GET параметров
    *
    * @param string $string
    *
    * @return array
    */     
    public function getGet($string)
    {
        return $this->router->hashFromUrl($string);
    }
    
    /**
    * Генерирует URL согласно роутам и режиму
    *
    * @param string $string
    * @param bool $mode
    *
    * @return string
    */  
    protected function createUrl($param, $mode = false)
    { 
        if (isset($mode['show_script'])) {
            unset($mode['show_script']);
        }
        
        if (is_array($mode) && !empty($this->config['url'])) {
            $config = array_merge($this->config['url'], $mode);
        } elseif (!is_array($mode) && !empty($this->config['url'])) {
            $config = $this->config['url'];
        } 

        $protocol   = !empty($config['https']) ? 'https://' : 'http://';
        $hostName   = $this->request->getHostName();
        $scriptName = null;
        

        if (!empty($config['show_script'])) {
            $query = trim($_SERVER['PHP_SELF'], '/');
            $scriptName = '/'. explode('/', $query)[0]; 
        }
       
        if (true === $mode) {
            $basePath = $protocol. $hostName . $scriptName ;
        } else {
            $basePath = !empty($config['absolute']) ? $protocol . $hostName . $scriptName : $scriptName;        
        }
     
        if (isset($config['pretty']) && false === $config['pretty']) {
            return $basePath .'?'. http_build_query($param);    
        } else {
            $param = $this->router->hashFromParam($param);
            return $basePath .'/'. implode('/', $param);
        }
    }
} 



















