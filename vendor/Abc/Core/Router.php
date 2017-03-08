<?php

namespace ABC\Abc\Core;

/** 
 * Класс Router
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
class Router
{

    protected $config;
    protected $parser;    
    protected $defaultRoute;
    protected $hash;
    
    /**
    * @param object $abc
    */ 
    public function __construct($abc)
    {
        $this->parser = $abc->newService('RouteParser');
        $this->config = $abc->getConfig(); 
        $this->defaultRoute = arrayStrtolower($this->config['default_route']);
    }     
    
    /**
    * Преобразует строку URL в массив GET
    *
    * @param string $string
    *
    * @return array
    */    
    public function createGetFrom($string)
    {
        $param = $this->hashFromString($string);
        return $this->generateGet($param);    
    }     
    
    /**
    * Преобразует URL в массив согласно роутам
    *
    * @param string $string
    *
    * @return array
    */    
    public function hashFromUrl($string)
    {
        $param = $this->hashFromString($string);
        return $this->convertUri($param, $string);    
    } 
    
    /**
    * Преобразует строку URL в массив hash 
    *
    * @param string $string
    *
    * @return array
    */    
    public function hashFromString($string)
    {   
        if (false !== strpos($string, '?')) {
            mb_parse_str(trim($string, '/?'), $param); 
            $param = $this->hashFromParam($param);
        } else {
            $param  = explode('/', trim($string, '/'));  
        }
     
        return $param;    
    } 
  
    /**
    * Генерирует массив HASH
    *
    * @param array $param
    *
    * @return array
    */    
    public function hashFromParam($param)
    {
        $this->hash = array_values(array_slice($param, 0, 2));
        $get = array_slice($param, 2);
        
        foreach ($get as $key => $value) {
            array_push($this->hash, $key);
            array_push($this->hash, $value);
        }      
        return $this->hash;
    }     
    
    /**
    * Преобразует массив URI в массив GET согласно роутам
    *
    * @param array $uriHash
    *
    * @return array
    */    
    public function convertUri($uriHash, $string = '')
    {    
        if (empty($this->config['route_rules'])) {
            return $this->defaultGet($uriHash);
        }
         
        return $this->parser->parseRoutes($string);
    }
    
    /**
    * Устанавливает GET по умолчанию
    *
    * @param array $param
    *
    * @return array
    */    
    public function defaultGet($param)
    {
        $app = ['controller' => empty($param[0]) ? $this->defaultRoute['controller'] : $param[0],
                'action'     => empty($param[1]) ? $this->defaultRoute['action'] : $param[1]
        ];
     
        $param = array_slice($param, 2);
        $get   = $this->generateGet($param);
        return array_merge($app, $get);
    }    
    
    /**
    * Генерирует GET из HASH
    *
    * @param array $uriHash
    *
    * @return array
    */    
    public function generateGet($param)
    {
        $get = [];
       
        foreach ($param as $n => $value) {
            if ($n & 1) {
                $get[$key] = $value;  
            } else {
                $key = $value;
            }
        }
     
        return $get;
    }    
}

