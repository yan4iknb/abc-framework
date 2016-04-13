<?php

namespace ABC\Abc\Builders;

use ABC\Abc;
use ABC\Abc\Builders\AbcBuilder;
/** 
 * Класс DicBuilder
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  

class UrlBuilder extends AbcBuilder
{
    /**
    * @var array
    */ 
    protected $service = 'Url';

    /**
    * Строит сервис.
    * 
    * @param bool $global
    *
    * @return void
    */        
    protected function buildService($global = false)
    { 
        $component = '\ABC\Abc\Components\\'. $this->service .'\\'. $this->service;
        $typeService = $global ? 'setGlobal' : 'set';
        $config = $this->config;
        $router = Abc::getFromStorage('Router');
      
        $this->container->$typeService(
            $this->service, 
            function() use ($component, $config, $router) {
                $obj = new $component;
                $obj->config = $config;
                $obj->router = $router;   
                return $obj;
            }
        );
    }   
}
