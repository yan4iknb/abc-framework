<?php

namespace ABC\Abc\Core;

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
            trigger_error(ABC_OVERFLOW_EX 
                         . 'Service '. $serviceId .'  is already installed.',
                         E_USER_WARNING);
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
     
        trigger_error(ABC_OUT_OF_BOUNDS_EX
                     .'Service '. $serviceId .' not found.', 
                     E_USER_WARNING);
    }

    /**
    * Внедряет один сервис в другой, создавая третий
    *
    * @param string $dependenceId
    * @param string $serviceId    
    * @param string $newService
    * @param array $property
    *
    * @return object
    */ 
    public function injection($dependenceId, $serviceId, $newService = null, $property = [])
    {
        $serviceId = $this->validateService($serviceId);
        
        if (empty($newService)) {
            $newService = $serviceId;
        } else {
            $newService = $this->validateService($newService);        
        }
        
        if (isset($this->serviceSynthetic[$newService])) {
            trigger_error(ABC_LOGIC_EX
                          .'Service '. $newService 
                          .' created synthetically. Impossible to implement services according to the synthetic',
                          E_USER_WARNING);
        }
     
        $dependenceId = $this->validateService($dependenceId);
        
        if (!empty($property) && !is_array($property)) {
            trigger_error(ABC_INVALID_ARGUMENT_EX
                         .'Property should be a array',
                         E_USER_WARNING); 
        }
        
        $objService = $this->get($serviceId);
        
        if (false === $objService) {
            trigger_error(ABC_LOGIC_EX
                         .'Service '. $serviceId .' is not registered in a container',
                         E_USER_WARNING);
        }
        
        $objDependence = $this->get($dependenceId);
        
        if (false === $objDependence) {
            trigger_error(ABC_LOGIC_EX
                         .'Service '. $dependenceId .' is not registered in a container',
                         E_USER_WARNING);
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
    * Проверяет наличие сервиса в хранилище
    *
    * @param string $ServiceId
    *
    * @return void
    */       
    public function checkService($ServiceId)
    {
        $ServiceId = $this->validateService($ServiceId);
        return isset($this->ServiceStorage[$ServiceId]);
    }  

    /**
    * Инициализирует и возвращает новый объект сервиса, даже если он заморожен
    *
    * @param string $ServiceId
    *
    * @return object
    */      
    public function getNew($ServiceId)
    {
        $ServiceId = $this->validateService($ServiceId);
        
        if (isset($this->ServiceStorage[$ServiceId])) {
            return $this->ServiceStorage[$ServiceId]->__invoke();
        }
     
        return false;
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
            trigger_error(ABC_INVALID_ARGUMENT_EX
                         .'ID service should be a string',
                         E_USER_WARNING); 
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
            trigger_error(ABC_INVALID_ARGUMENT_EX
                         .'Callable must be a function of anonymity is conferred',
                         E_USER_WARNING); 
        }
        
        return $callable;
    }
}
