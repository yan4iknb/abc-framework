<?php

namespace ABC\Abc\Builders;

/** 
 * Класс AbcBuilder
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
abstract class AbcBuilder 
{
    /**
    * @var array
    */ 
    public $config;

    /**
    * @var array
    */ 
    public $component;

    /**
    * @var ServiceLocator
    */ 
    public $locator; 
    
    /**
    * Контракт
    */ 
    abstract protected function buildService($global);
    
    /**
    * Получает сервис из локатора если он есть
    * или сначала помещает его туда
    *
    * @return object
    */    
    public function newService()
    {  
        if (!$this->locator->checkService($this->service)) {
            $this->buildService();
        }
        
        return $this->locator->getNew($this->service);
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
        if (!$this->locator->checkService($this->service)) { 
            $this->buildService(true);
        }
      
        return $this->locator->get($this->service);
    }     
    
}
