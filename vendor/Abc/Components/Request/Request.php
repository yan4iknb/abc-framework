<?php

namespace ABC\Abc\Core;

use ABC\Abc\Core\BaseRequest;

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


