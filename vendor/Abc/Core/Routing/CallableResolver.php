<?php

namespace ABC\Abc\Core\Routing;

use ABC\Abc\Core\Base;

/** 
 * Класс Executor
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/
 */   
class CallableResolver
{
    /**
    * @param object $abc
    */ 
    public function __construct($abc)
    {
        $this->abc = $abc;
    }     

    /**
    * @param object $abc
    */ 
    public function get($pattern = null, $callable = null)
    {
        if (null === $pattern) {
            $this->create404();
        }
    }
    
    /**
    * Если не найден контроллер или экшен, активирует 
    * базовый контроллер с генерацией 404 заголовка
    *
    * @param string $controller
    *  
    * @return void
    */        
    public function create404()
    {   
        $base = new Base();
        $base->abc = $this->abc;
        $base->action404('Nothing, ');
    }  
    
}
