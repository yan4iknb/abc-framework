<?php

namespace ABC\abc\core;

/** 
 * Сервис-локатор
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */   
class ServiceLocator 
{ 
    protected $ServiceStorage = [];
    protected $ServiceFrozen  = [];
    protected static $ObjectStorage = [];  

    /**
    * Записывает сервис в хранилище
    *
    * @param string $ServiceId
    * @param callable $callable
    *
    * @return void
    */  
    public function set($ServiceId, $callable)
    {
        $ServiceId = $this->validateService($ServiceId);
        $callable  = $this->validateCallable($callable);
        
        if (isset($this->ServiseStorage[$ServiceId])) {
            return false;
        }

        $this->ServiceStorage[$ServiceId] = $callable;   
    }
    
    /**
    * Записывает сервис в глобальное хранилище
    *
    * @param string $ServiceId
    * @param callable $callable
    *
    * @return void
    */  
    public function setGlobal($ServiceId, $callable)
    {
        $this->set($ServiceId, $callable);
        $this->ServiceFrozen[strtolower($ServiceId)]  = true;    
    }
    
    /**
    * Инициализирует и возвращает объект сервиса
    *
    * @param string $ServiceId
    *
    * @return object
    */      
    public function get($ServiceId)
    {
        $ServiceId = $this->validateService($ServiceId);
     
        if (isset($this->ServiseFrozen[$ServiceId])) {
         
            if (empty(self::$ObjectStorage[$ServiceId])) {
                self::$ObjectStorage[$ServiceId] = $this->ServiceStorage[$ServiceId]->__invoke();
            }
         
            return self::$ObjectStorage[$ServiceId];
         
        } elseif (!empty($this->ServiceStorage[$ServiceId])) {
            return $this->ServiceStorage[$ServiceId]->__invoke();
        }
     
        return false;
    }  
    
    /**
    * Удаляет объект из хранилища
    *
    * @param string $ServiceId
    *
    * @return void
    */       
    public function unsetService($ServiceId)
    {
        $ServiceId = $this->validateService($ServiceId);
        
        if (!isset($this->ServiceStorage[$ServiceId])) {
            return false;
        }

        unset($this->ServiceStorage[$ServiceId]);
        unset(self::$ObjectStorage[$ServiceId]);
    } 
    
    /**
    * Проверяет корректность ID сервиса 
    *
    * @param string $ServiceId
    *
    * @return string
    */
    protected function validateService($ServiceId)
    {
        if (empty($ServiceId) || !is_string($ServiceId)) {
            throw new \InvalidArgumentException('ID service should be a string', E_USER_WARNING); 
        }
     
        return strtolower($ServiceId);
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
