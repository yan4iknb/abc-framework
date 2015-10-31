<?php

namespace ABC\Abc\Builders;

use ABC\Abc\Builders\AbcBuilder;
use ABC\Abc\Components\Sqldebug\SqlDebug;

/** 
 * Класс PdoBuilder
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  

class PdoBuilder extends AbcBuilder
{
    /**
    * @var array
    */ 
    protected $service = 'Pdo';   

    /**
    * Строит сервис.
    * 
    * @return void
    */        
    protected function buildService($global = false)
    { 
        $component = '\ABC\Abc\Components\\'. $this->service .'\\'. $this->service;    
        $data = @$this->config[strtolower($this->service)] ?: [];
        $typeService = $global ? 'setGlobal' : 'set';
        
        $this->locator->$typeService(
            $this->service, 
            function() use ($component, $data) {
                $data['debugger'] = isset($data['debug']) ? new SqlDebug(new View) : null;
                return new $component($data);
            }
        );
    }   
}
