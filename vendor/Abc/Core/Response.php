<?php

namespace ABC\Abc\Core;

/** 
 * Класс Constants
 * Предустановленные константы фреймворка
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */  

class Response
{

    static $marker;

    /**
    * Константы кодов иерархии исключений SPL
    */  
    const EXCEPTION         = '[000]'; 
    const BAD_FUNCTION_CALL = '[001]';
    const BAD_METHOD_CALL   = '[002]';    
    const DOMAIN            = '[003]';
    const INVALID_ARGUMENT  = '[004]';
    const LENGTH            = '[005]';    
    const LOGIC             = '[006]';
    const OUT_OF_BOUNDS     = '[007]';    
    const OUT_OF_RANGE      = '[008]';         
    const OVERFLOW          = '[009]';    
    const RANGE             = '[010]'; 
    const RUNTIME           = '[011]';    
    const UNDERFLOW         = '[012]';
    const UNEXPECTED_VALUE  = '[013]';
   
    /**
    * 
    */     
    public static function exception($message)
    {
        self::$marker = self::EXCEPTION;
        self::error($message);
    }
    
    /**
    * 
    */ 
    public static function badFunctionCallException($message)
    {
        self::$marker = self::BAD_FUNCTION_CALL;
        self::error($message);
    }
    
    /**
    * 
    */ 
    public static function badMethodCallException($message)
    {
        self::$marker = self::BAD_METHOD_CALL;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function domainException($message)
    {
        self::$marker = self::DOMAIN;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function invalidArgumentException($message)
    {
        self::$marker = self::INVALID_ARGUMENT;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function lengthException($message)
    {
        self::$marker = self::LENGTH;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function logicException($message)
    {
        self::$marker = self::LOGIC;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function outOfBoundsException($message)
    {
        self::$marker = self::OUT_OF_BOUNDS;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function outOfRangeException($message)
    {
        self::$marker = self::OUT_OF_RANGE;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function overflowException($message)
    {
        self::$marker = self::OVERFLOW;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function rangeException($message)
    {
        self::$marker = self::RANGE;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function runtimeException($message)
    {
        self::$marker = self::RUNTIME;
        self::error($message);
    }        
    
    /**
    * 
    */ 
    public static function underflowException($message)
    {
        self::$marker = self::UNDERFLOW;
        self::error($message);
    }        
    
    /**
    * 
    */ 
    public static function unexpectedValueException($message)
    {
        self::$marker = self::UNEXPECTED_VALUE;
        self::error($message);
    }


    protected static function error($message)
    {
        trigger_error(self::$marker . $message, E_USER_WARNING);
    }
}



















































