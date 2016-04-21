<?php

namespace ABC\Abc\Core;

use ABC\Abc\Core\Exception\AbcError;

/** 
 * DI контейнер
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class Container
{ 
    protected $serviceStorage = [];
    protected $serviceFrozen  = [];
    protected $serviceSynthetic  = [];    
    protected static $objectStorage = [];  

    /**
    * Записывает сервис в хранилище
    *
    * @param string $ServiceId
    * @param callable $callable
    *
    * @return void
    */ 
    public function set($serviceId, $callable)
    {
        $serviceId = $this->validateService($serviceId);
        $callable  = $this->validateCallable($callable);
      
        if (isset($this->serviceStorage[$serviceId])) {
            AbcError::overflow($serviceId . ABC_ALREADY_SERVICE);
        }
     
        $this->serviceStorage[$serviceId] = $callable; 
    }
    
    /**
    * Записывает сервис в глобальное хранилище
    *
    * @param string $serviceId
    * @param callable $callable
    *
    * @return void
    */  
    public function setGlobal($serviceId, $callable)
    { 
        $this->set($serviceId, $callable);
        $this->serviceFrozen[strtolower($serviceId)]  = true;    
    }
    
    /**
    * Инициализирует и возвращает объект сервиса
    *
    * @param string $serviceId
    *
    * @return object
    */      
    public function get($serviceId)
    { 
        $serviceId = $this->validateService($serviceId);
     
        if (isset($this->serviceFrozen[$serviceId])) {
         
            if (!isset(self::$objectStorage[$serviceId])) {
                self::$objectStorage[$serviceId] = $this->serviceStorage[$serviceId]->__invoke();
            }
           
            return self::$objectStorage[$serviceId];
         
        } elseif (isset($this->serviceStorage[$serviceId])) {
            return $this->serviceStorage[$serviceId]->__invoke();
        }
     
        AbcError::outOfBounds($serviceId . ABC_NOT_FOUND_SERVICE);
    }

    /**
    * Проверяет наличие сервиса в хранилище
    *
    * @param string $ServiceId
    *
    * @return void
    */       
    public function checkService($serviceId)
    {
        $serviceId = $this->validateService($serviceId);
        return isset($this->serviceStorage[$serviceId]);
    }  

    /**
    * Инициализирует и возвращает новый объект сервиса, даже если он заморожен
    *
    * @param string $ServiceId
    *
    * @return object|bool
    */      
    public function getNew($serviceId)
    {
        $serviceId = $this->validateService($serviceId);
        
        if (isset($this->serviceStorage[$serviceId])) {
            return $this->serviceStorage[$serviceId]->__invoke();
        }
     
        return false;
    } 

    /**
    * Объявляет сервис синтетическим, запрещенным к внедрению в него зависимостей
    *
    * @param string $ServiceId
    *
    * @return void
    */      
    public function serviceSynthetic($serviceId)
    {
        $serviceId = $this->validateService($serviceId); 
        $this->serviceSynthetic[$serviceId] = true;
    }     
    /**
    * Проверяет корректность ID сервиса 
    *
    * @param string $serviceId
    *
    * @return string
    */
    protected function validateService($serviceId)
    {
        if (empty($serviceId) || !is_string($serviceId)) {
            AbcError::invalidArgument(ABC_INVALID_SERVICE_NAME); 
        }
     
        return strtolower($serviceId);
    }
    
    /**
    * Проверяет корректность анонимной функции 
    *
    * @param callable $callable
    *
    * @return callable
    */
    protected function validateCallable($callable)
    {      
        if (!is_callable($callable)) {
            AbcError::invalidArgument(ABC_INVALID_CALLABLE); 
        }
        
        return $callable;
    }
}
