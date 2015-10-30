<?php

namespace ABC\abc\core\debugger;

/** 
 * Класс DebugException 
 * Адаптирует trigger_error к Exception
 * для корректного выброса исключения
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */  

class TriggerException
{

    /**
    * Конструктор
    * 
    */     
    public function __construct() 
    { 
        set_error_handler([$this, 'triggerErrorHandler']);
    }

    /**
    * Отлавливает trigger_error
    *
    * @return void
    */   
    public function triggerErrorHandler($code, $message, $file, $line) 
    {  
        $exceptionCode = ;
        
        switch ($exceptionCode) {
        
            case ABC_INVALID_ARGUMENT_EXCEPTION :
                throw new \InvalidArgumentException($message, $code);
            
            case :
            
            
            case :
            
            
            case :
            
            
            case :
            
            
            case :
            
            default :
            
            
            
            
            
        }
    }
}  





























