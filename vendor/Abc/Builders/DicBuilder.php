<?php

namespace ABC\Abc\Builders;

use ABC\Abc\Builders\AbcBuilder;

/** 
 * Сборка дебаггера SQL 
 */ 
use ABC\Abc\Components\Sqldebug\SqlDebug;
use ABC\Abc\Components\Sqldebug\View;

/** 
 * Класс DicBuilder
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  

class DicBuilder extends AbcBuilder
{
    /**
    * @var array
    */ 
    protected $service = 'DiC';

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
        
        $this->container->$typeService(
            $this->service, 
            function() use ($component) {
                return new $component;
            }
        );
    }   
}
