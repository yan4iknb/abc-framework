<?php

namespace ABC\Abc\Core;

use ABC\Abc;

/** 
 * Фронт-контролер
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
class BaseController
{ 

    public $config;
    

    /**
    * Генерирует 404 Not Found
    *
    * @param string $controller
    *
    * @return void
    */    
    public function action404($controller)
    {
        if (isset($this->config['error_mod']) && $this->config['error_mod'] === 'debug') {
            throw new \DomainException('<b>'. $controller .'</b> not found ', E_USER_WARNING); 
        }
        
        header("HTTP/1.1 404 Not Found");
        exit();
    }
}