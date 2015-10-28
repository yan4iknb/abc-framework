<?php

namespace ABC\Abc\Core;

use ABC\Abc\Components\Dic\DiC;

/** 
 * Сервис-локатор
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class ServiceLocator extends DiC
{ 
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
}
