<?php

namespace ABC\Abc\Core\Routing;


/** 
 * Класс Convertor
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/
 */   
class Router
{
    use ParserTrait;
   
    protected $defaultRoute;
    protected $showScript;
    /**
    * @param object $abc
    */ 
    public function __construct($config)
    {
        $this->showScript   = $config['uri_manager']['show_script'];
        $this->defaultRoute = $config['default_route'];
        $this->defaultRoute = array_map('strtolower', $this->defaultRoute);
        $this->routeRules   = isset($config['route_rules']) ? $config['route_rules'] : [];
    }     

    /**
    * Разбирает массив HASH в массив GET по правилам роутинга
    *
    * @return array
    */    
    public function createGet($path)
    {
        $hash = $this->convertPathToHash($path);
        return $this->convertHashToGet($hash, $path);
    }
    
    ///**
    //* Разбор правил маршрутизации
    //*
    //* @param string $queryString
    //*
    //* @return array
    //*/    
    //public function parseRoutes($queryString)
    //{
        //return $this->parseRoutes($queryString);
    //}
    
    /**
    * Генерирует массив HASH
    *
    * @param array $param
    *
    * @return array
    */    
    public function createHashFromParam($param)
    {
        $hash = array_values(array_slice($param, 0, 2));
        $get = array_slice($param, 2);
        
        foreach ($get as $key => $value) {
            array_push($hash, $key);
            array_push($hash, $value);
        }
        
        return $hash;
    }
    
    /**
    * Преобразует строку URL в массив GET
    *
    * @param string $request
    *
    * @return array
    */    
    public function createGetFromPath($path)
    {
        $param = $this->createHashFromString($path);
        return $this->generateDefaultGet($param);    
    } 
    
    /**
    * Преобразует URL в массив HASH
    *
    * @return array
    */    
    protected function convertPathToHash($path)
    {
        $hash = explode('/', trim($path, '/'));
        
        if (!empty($this->showScript)) {
            array_shift($hash);
        }
        
        return $hash;
    }
    
    /**
    * Преобразует строку PATH в массив HASH 
    *
    * @param string $queryString
    *
    * @return array
    */    
    protected function createHashFromString($queryString)
    {   
        if (false !== strpos($queryString, '?')) {
            mb_parse_str(trim($queryString, '/?'), $param); 
            $param = $this->createHashFromParam($param);
        } else {
            $param  = explode('/', trim($queryString, '/'));  
        }
     
        return $param;    
    }  
    
    /**
    * Преобразует PATH в массив HASH согласно роутам
    *
    * @param string $request
    *
    * @return array
    */    
    public function convertPathToGet($path)
    {
        $request = $this->createHashFromString($path);
        return $this->convertHashToGet($hash, $path);    
    } 
    
    /**
    * Преобразует массив URI в массив GET согласно роутам
    *
    * @param array $hash
    * @param string $path
    *
    * @return array
    */    
    protected function convertHashToGet($hash, $path = '')
    {    
        if (empty($this->routeRules)) {
            return $this->defaultGet($hash);
        }
     
        return $this->parseRoutes($path);
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
        $get   = $this->generateDefaultGet($param);
        
        return array_merge($app, $get);
    }    
    
    /**
    * Генерирует GET из HASH
    *
    * @param array $param
    *
    * @return array
    */    
    public function generateDefaultGet($param)
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
