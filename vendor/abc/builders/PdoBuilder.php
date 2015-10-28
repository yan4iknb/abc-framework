<?php

namespace ABC\Abc\Builders;

use ABC\Abc\Builders\AbcBuilder;

/** 
 * Сборка дебаггера SQL 
 */ 
use ABC\Abc\Components\Sqldebug\SqlDebug;
use ABC\Abc\Components\Sqldebug\View;

/** 
 * Класс Mysqli
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
    protected $service = 'pdo';   

    /**
    * Строит сервис.
    * 
    * @return void
    */        
    protected function buildService($global = false)
    { 
        $component = '\ABC\abc\components\\'. $this->service .'\\'. $this->service;    
        $data = @$this->config[$this->service] ?: [];
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
