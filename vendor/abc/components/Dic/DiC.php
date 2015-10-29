<?php

namespace ABC\Abc\Components\Dic;

/** 
 * DI контейнер
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class DiC
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
            throw new \OverflowException('Service <b>'. $serviceId .'</b>  is already installed.', E_USER_WARNING);
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
     
        if (isset($this->ServiseFrozen[$serviceId])) {
         
            if (!isset(self::$objectStorage[$serviceId])) {
                self::$ObjectStorage[$serviceId] = $this->ServiceStorage[$serviceId]->__invoke();
            }
         
            return self::$objectStorage[$serviceId];
         
        } elseif (isset($this->serviceStorage[$serviceId])) {
            return $this->serviceStorage[$serviceId]->__invoke();
        }
     
        throw new \OutOfBoundsException('Service '. $serviceId .' not found.', E_USER_WARNING);
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
            throw new \LogicException('Service <b>'. $newService 
                                     .'</b> created synthetically. Impossible to implement services according to the synthetic', E_USER_WARNING);
        }
     
        $dependenceId = $this->validateService($dependenceId);
        
        if (!empty($property) && !is_array($property)) {
            throw new \InvalidArgumentException('Property should be a array', E_USER_WARNING); 
        }
        
        $objService = $this->get($serviceId);
        
        if (false === $objService) {
            throw new \LogicException('Service <b>'. $serviceId .'</b> is not registered in a container', E_USER_WARNING);
        }
        
        $objDependence = $this->get($dependenceId);
        
        if (false === $objDependence) {
            throw new \LogicException('Service <b>'. $dependenceId .'</b> is not registered in a container', E_USER_WARNING);
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
            throw new \InvalidArgumentException('ID service should be a string', E_USER_WARNING); 
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
            throw new \InvalidArgumentException('Callable must be a function of anonymity is conferred', E_USER_WARNING); 
        }
        
        return $callable;
    }    
}

