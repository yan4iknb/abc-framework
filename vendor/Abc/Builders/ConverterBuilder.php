<?php

namespace ABC\Abc\Builders;

use ABC\Abc\Builders\AbcBuilder;

/** 
 * Класс DicBuilder
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  

class ConverterBuilder extends AbcBuilder
{
    /**
    * @var array
    */ 
    protected $service = 'Converter';

    /**
    * Строит сервис.
    * 
    * @return void
    */        
    protected function buildService($global = false)
    { 
        $component = '\ABC\Abc\Components\\'. $this->service .'\\'. $this->service;
        $typeService = $global ? 'setGlobal' : 'set';
        
        $this->locator->$typeService(
            $this->service, 
            function() use ($component) {
                return new $component;
            }
        );
    }   
}
