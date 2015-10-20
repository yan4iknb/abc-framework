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
class ServiseLocator 
{ 
    protected $ServiseStorage = [];
    protected $ServiseFrozen  = [];
    protected static $ObjectStorage = [];  

    /**
    * Записывает сервис в хранилище
    *
    * @param string $ServiseId
    * @param callable $callable
    *
    * @return void
    */  
    public function set($ServiseId, $callable)
    {
        $ServiseId = $this->validateServise($ServiseId);
        $callable  = $this->validateCallable($callable);
        
        if (isset($this->ServiseStorage[$ServiseId])) {
            return false;
        }

        $this->ServiseStorage[$ServiseId] = $callable;   
    }
    
    /**
    * Записывает сервис в глобальное хранилище
    *
    * @param string $ServiseId
    * @param callable $callable
    *
    * @return void
    */  
    public function setGlobal($ServiseId, $callable)
    {
        $this->set($ServiseId, $callable);
        $this->ServiseFrozen[strtolower($ServiseId)]  = true;    
    }
    
    /**
    * Инициализирует и возвращает объект сервиса
    *
    * @param string $ServiseId
    *
    * @return object
    */      
    public function get($ServiseId)
    {
        $ServiseId = $this->validateServise($ServiseId);
     
        if (isset($this->ServiseFrozen[$ServiseId])) {
         
            if (empty(self::$ObjectStorage[$ServiseId])) {
                self::$ObjectStorage[$ServiseId] = $this->ServiseStorage[$ServiseId]->__invoke();
            }
         
            return self::$ObjectStorage[$ServiseId];
         
        } elseif (!empty($this->ServiseStorage[$ServiseId])) {
            return $this->ServiseStorage[$ServiseId]->__invoke();
        }
     
        return false;
    }  
    
    /**
    * Удаляет объект из хранилища
    *
    * @param string $ServiseId
    *
    * @return void
    */       
    public function unsetServise($ServiseId)
    {
        $ServiseId = $this->validateServise($ServiseId);
        
        if (!isset($this->ServiseStorage[$ServiseId])) {
            return false;
        }

        unset($this->ServiseStorage[$ServiseId]);
        unset(self::$ObjectStorage[$ServiseId]);
    } 
    
    /**
    * Проверяет корректность ID сервиса 
    *
    * @param string $ServiseId
    *
    * @return string
    */
    protected function validateServise($ServiseId)
    {
        if (empty($ServiseId) || !is_string($ServiseId)) {
            trigger_error('ID service should be a string', E_USER_WARNING); 
        }
     
        return strtolower($ServiseId);
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
            trigger_error('Callable must be a function of anonymity is conferred', E_USER_WARNING); 
        }
        
        return $callable;
    }    
}
