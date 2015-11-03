<?php

namespace ABC\Abc\Core;

/** 
 * Класс AbcFramework
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
class Router
{
    public $config;
    public $routes;
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
        
        if (empty($this->routes)) {
            return $this->defaultGet($uriHash);
        }
        
        return $this->routeGet($uriHash);
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
     
        return array_merge($get, $app);
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

