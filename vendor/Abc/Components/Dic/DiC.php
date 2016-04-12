<?php

namespace ABC\Abc\Components\Dic;

use ABC\Abc\Core\ServiceLocator;

/** 
 * DI контейнер
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class DiC extends ServiceLocator
{ 
    
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
            trigger_error(ABC_LOGIC_EX .
                          $newService . ABC_SYNTHETIC_SERVICE,
                          E_USER_WARNING);
        }
     
        $dependenceId = $this->validateService($dependenceId);
        
        if (!empty($property) && !is_array($property)) {
            trigger_error(ABC_INVALID_ARGUMENT_EX .
                          ABC_INVALID_PROPERTY,
                          E_USER_WARNING); 
        }
        
        $objService = $this->get($serviceId);
        
        if (false === $objService) {
            trigger_error(ABC_LOGIC_EX .
                         $serviceId . ABC_NOT_REGISTERED_SERVICE,
                         E_USER_WARNING);
        }
        
        $objDependence = $this->get($dependenceId);
        
        if (false === $objDependence) {
            trigger_error(ABC_LOGIC_EX
                         . $dependenceId . ABC_NOT_REGISTERED_SERVICE,
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
}
