<?php

namespace ABC\Abc\Components\Dic;

use ABC\Abc\Core\Response;
use ABC\Abc\Core\Container;

/** 
 * DI контейнер
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class DiC extends Container
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
            Response::LogicError($newService . ABC_SYNTHETIC_SERVICE);
        }
     
        $dependenceId = $this->validateService($dependenceId);
        
        if (!empty($property) && !is_array($property)) {
            Response::invalidArgumentError(ABC_INVALID_PROPERTY); 
        }
        
        $objService = $this->get($serviceId);
        
        if (false === $objService) {
            Response::LogicError($serviceId . ABC_NOT_REGISTERED_SERVICE);
        }
        
        $objDependence = $this->get($dependenceId);
        
        if (false === $objDependence) {
            Response::LogicError($dependenceId . ABC_NOT_REGISTERED_SERVICE);
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
