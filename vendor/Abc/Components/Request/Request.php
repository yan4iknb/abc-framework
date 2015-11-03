<?php

namespace ABC\Abc\Components\Request;

use ABC\Abc\Core\Request as BaseRequest;

/** 
 * Класс Request
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
class Request extends BaseRequest
{

    /**
    * Конструктор
    *
    * @param object $router
    */    
    public function __construct($router)
    {
        parent::__construct($router);
    } 

    /**
    * Инициализация POST параметров 
    *
    * @param string $key
    * @param string $default
    *
    * @return string
    */        
    public function iniPOST($key, $default = null)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
}
