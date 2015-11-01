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
    
    protected $default   = [
                            'controller' => 'main', 
                            'action'     => 'index'
              ];


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
        // не реализовано
        return $this->default;
    }      
}


