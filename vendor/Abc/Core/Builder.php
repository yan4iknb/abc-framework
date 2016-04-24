<?php

namespace ABC\Abc\Core;

/** 
 * Класс Builder
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class Builder 
{
 
    protected $serviceId; 
    protected $container;
    
    public function __construct($serviceId, $abc)
    {
        $this->serviceId = $serviceId;
        $this->abc = $abc;
        $this->container = $abc->getContainer();
    }
    
    /**
    * Получает сервис из локатора если он есть
    * или сначала помещает его туда
    *
    * @return object
    */    
    public function newService()
    {  
        if (!$this->container->checkService($this->serviceId)) {
            $this->buildService();
        }
        
        return $this->container->getNew($this->serviceId);
    }
    
    /**
    * Получает сервис из локатора если он есть
    * или сначала помещает его туда
    * (по принципу Singleton)
    *
    * @return object
    */    
    public function getService()
    { 
        if (!$this->container->checkService($this->serviceId)) { 
            $this->buildService(true);
        }
      
        return $this->container->get($this->serviceId);
    } 
    
    /**
    * Строит сервис.
    * 
    * @param bool $global
    *
    * @return void
    */         
    protected function buildService($global = false)
    {
        $abc = $this->abc;    
        $component = '\ABC\Abc\Components\\'. $this->serviceId .'\\'. $this->serviceId;   
        $typeService = $global ? 'setAsShared' : 'set';
        
        $this->container->$typeService(
            $this->serviceId,
            function() use ($component, $abc) {   
                return new $component($abc);
            }
        );
    }  
    
}
