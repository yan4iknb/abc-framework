<?php

namespace ABC\Abc\Builders;

use ABC\Abc;
use ABC\Abc\Builders\AbcBuilder;
use ABC\Abc\Components\Url\Url;

/** 
 * Класс DicBuilder
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  

class PaginatorBuilder extends AbcBuilder
{
    /**
    * @var array
    */ 
    protected $service = 'Paginator';

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
        $get = Abc::GET();
        $url = Abc::getService('Url');
        $this->locator->$typeService(
            $this->service, 
            function() use ($component, $get, $url) {
                $obj = new $component;
                $obj->get = $get;
                $obj->url = $url;
                return $obj;
            }
        );
    }   
}
