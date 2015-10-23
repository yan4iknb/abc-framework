<?php
namespace ABC\Abc\Builders;

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
 * @license http://abc-framework.com/license/ 
 */  

class MysqliBuilder 
{
    /**
    * @var array
    */ 
    protected $service = 'mysqli';   

    /**
    * @var array
    */ 
    public $config;
    
    /**
    * @var ServiceLocator
    */ 
    public $locator;
    
    /**
    * Получает сервис из локатора если он есть
    * или сначала помещает его туда
    *
    * @return object
    */    
    public function get()
    {            
        if (!$this->locator->checkService($this->service)) {
            $this->buildService();
        }
        
        return $this->locator->get($this->service);
    }
    
    /**
    * Строит сервис, удовлетворяя зависимости.
    * 
    * @return void
    */        
    protected function buildService()
    { 
        $component = '\ABC\abc\components\\'. $this->service .'\\'. $this->service;    
        $data = @$this->config[$this->service] ?: [];
        
        $this->locator->setGlobal(
            $this->service, 
            function() use ($component, $data) {
                $data['debugger'] = isset($data['debug']) ? new SqlDebug(new View) : null;
                return new $component($data);
            }
        );
    }   
}












