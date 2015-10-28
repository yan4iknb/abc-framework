<?php

namespace ABC\Abc\Core;

use ABC\Abc;

/** 
 * Фронт-контролер
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */   
class BaseController
{ 

    public $config;
    
    /**
    * Конструктор
    * 
    * @param array $appConfig
    * @param array $siteConfig
    */    
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
    * Генерирует 404 Not Found
    *
    * @param string $controller
    *
    * @return void
    */    
    public function action404($controller)
    {
        if (isset($this->config['debug_mod']) && $this->config['debug_mod'] === 'display') {
            throw new \DomainException('<b>'. $controller .'</b> not found ', E_USER_WARNING); 
        }
        
        header("HTTP/1.1 404 Not Found");
        exit();
    }
}