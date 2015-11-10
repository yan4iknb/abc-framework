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
    * Преобразует строку URL в массив
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
    * Преобразует строку URL в массив согласно роутам
    *
    * @param string $string
    *
    * @return array
    */    
    public function hashFromUrl($string)
    {
        $param = $this->hashFromString($string);
        return $this->convertUri($param);    
    } 
    
    /**
    * Преобразует строку URL в массив согласно роутам
    *
    * @param string $string
    *
    * @return array
    */    
    public function hashFromString($string)
    {
        $string = trim($string, '/?');
        
        if (false !== strpos($string, '&')) {
            mb_parse_str($string, $param); 
            $param = $this->hashFromQueryString($param);
        } else {
            $param  = explode('/', $string);
            
        }
     
        return $param;    
    } 
  
    /**
    * Генерирует массив HASH из QueryString
    *
    * @param array $param
    *
    * @return array
    */    
    public function hashFromQueryString($param)
    {
        $hash = array_values(array_slice($param, 0, 2));
        $get  = array_slice($param, 2);
      
        foreach ($get as $key => $value) {
            array_push($hash, $key);
            array_push($hash, $value);
        }
       
        return $hash;
        
    }     
    
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
    * Устанавливает GET по умолчанию
    *
    * @param array $param
    *
    * @return array
    */    
    public function defaultGet($param)
    {
        $app = ['controller' => @$param[0] ?: $this->config['defaultRoute']['controller'],
                'action'     => @$param[1] ?: $this->config['defaultRoute']['action']
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

