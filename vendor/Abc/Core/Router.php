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
    public $config;
    /**
    * Преобразует массив URI в массив GET
    *
    * @param array $uriHash
    *
    * @return array
    */    
    public function convertUri($uriHash)
    {
        if (empty($uriHash)) {
            return $this->default;
        }
        
        if (empty($this->config['routes'])) {
            return $this->defaultGet($uriHash);
        }
        
        return $this->routeGet($uriHash);
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
                if (preg_match('#^[a-z_]+[a-z0-9_\[\]]+$#ui', $key)) {
                    $get[$key] = $value;                
                }
            } else {
                $key = $value;
            }
        }
     
        return $get;
    }
    
    
    /**
    * Преобразует строку URL в массив согласно роутам
    *
    * @param string $string
    *
    * @return array
    */    
    public function hashFromUrl($string)
    {
        if (false !== strpos($string, '&')) {
            $param = $this->hashFromQueryString($string);
        } else {
            $param  = explode('/', trim($string, '/?'));        
        }
        
        return $this->convertUri($param);    
    }    
    
    /**
    * Генерирует массив HASH из QueryString
    *
    * @param string $query
    *
    * @return array
    */    
    public function hashFromQueryString($query)
    {
        parse_str($query, $param);
        $mods = array_values(array_slice($param, 0, 2));
        $get  = array_slice($param, 2);
        $hash = [];
        
        foreach ($get as $key => $value) {
            $hash[] = $key;
            $hash[] = $value;
        }
        
        return array_merge($mods, $hash);
        
    }  
    
    /**
    * Устанавливает GET по умолчанию
    *
    * @param array $uriHash
    *
    * @return array
    */    
    protected function defaultGet($uriHash)
    {
        $app = ['controller' => @$uriHash[0] ?: $this->config['defaultRoute']['controller'],
                'action'     => @$uriHash[1] ?: $this->config['defaultRoute']['action']
        ];
     
        $param = array_slice($uriHash, 2);
        $get   = $this->generateGet($param);
        return array_merge($app, $get);
    }
    
    /**
    * Устанавливает GET согласно роутам
    *
    * @param array $uriHash
    *
    * @return array
    */    
    protected function routeGet($uriHash)
    {
        // Не реализовано
        return $this->config->defaultRoute;
    }
}

