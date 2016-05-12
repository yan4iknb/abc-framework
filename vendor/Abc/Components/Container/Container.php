<?php

namespace ABC\Abc\Components\Container;

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
    public function setAsShared($serviceId, $callable)
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
    * Внедряет один сервис в другой, создавая третий
    *
    * @param string $serviceId
    * @param string $dependenceId    
    * @param string $newService
    * @param array $property
    *
    * @return object
    */ 
    public function injection($serviceId, $dependenceId, $newService = null, $property = [])
    {
        $serviceId = $this->validateService($serviceId);
        
        if (empty($newService)) {
            $newService = $serviceId;
        } else {
            $newService = $this->validateService($newService);        
        }
        
        if (isset($this->serviceSynthetic[$newService])) {
            AbcError::Logic($newService . ABC_SYNTHETIC_SERVICE);
        }
     
        $dependenceId = $this->validateService($dependenceId);
        
        if (!empty($property) && !is_array($property)) {
            AbcError::invalidArgument(ABC_INVALID_PROPERTY); 
        }
        
        $objService = $this->get($serviceId);
        
        if (false === $objService) {
            AbcError::Logic($serviceId . ABC_NOT_REGISTERED_SERVICE);
        }
        
        $objDependence = $this->get($dependenceId);
        
        if (false === $objDependence) {
            AbcError::Logic($dependenceId . ABC_NOT_REGISTERED_SERVICE);
        }
        
        $class = get_class($objService);
     
        $newCallable = function() use ($class, $objDependence, $property) {
            $obj = new $class($objDependence);
         
            foreach ($property as $key => $value) {
                $obj->$key = $value;
            }
            
            return $obj;
        };
     
        unset($objService);
        unset($objDependence);
        
        $this->serviceStorage[$newService] = $newCallable;
        $this->serviceSynthetic[$newService] = true;
    }
    
    /**
    * Удаляет объект из хранилища
    *
    * @param string $serviceId
    *
    * @return void
    */       
    public function unsetService($serviceId)
    {
        $serviceId = $this->validateService($serviceId);
        
        if (!isset($this->serviceStorage[$serviceId])) {
            return false;
        }
     
        unset($this->serviceStorage[$serviceId]);
        unset(self::$objectStorage[$serviceId]);
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
