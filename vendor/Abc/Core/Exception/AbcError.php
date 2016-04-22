<?php
namespace ABC\Abc\Core\Exception;
/** 
 * Класс AbcError 
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */  
class AbcError
{
    public static $marker;
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
    * Конструктор
    * 
    */     
    public function __construct() 
    {
        set_error_handler([$this, 'triggerErrorException']);
    }
    
    /**
    * Отлавливает trigger_error
    *
    * @return void
    */   
    public function triggerErrorException($code, $message, $file, $line) 
    { 
        if (error_reporting() & $code) {
         
            $type = substr($message, 0, 5);
         
            switch ($type) {
             
                case self::BAD_FUNCTION_CALL :
                    $exception = 'BadFunctionCallException';
                break;
                case self::BAD_METHOD_CALL :
                    $exception = 'BadMethodCallException';
                break;
                case self::DOMAIN :
                    $exception = 'DomainException';
                break;
                case self::INVALID_ARGUMENT :
                    $exception = 'InvalidArgumentException';
                break;
                case self::LENGTH :
                    $exception = 'LengthException';
                break;
                case self::LOGIC :
                    $exception = 'LogicException';
                break;
                case self::OUT_OF_BOUNDS :
                    $exception = 'OutOfBoundsException';
                break;
                case self::OUT_OF_RANGE :
                    $exception = 'OutOfRangeException';
                break;
                case self::OVERFLOW :
                    $exception = 'OverflowException';
                break;
                case self::RANGE:
                    $exception = 'RangeException';
                break;
                case self::RUNTIME:
                    $exception = 'RuntimeException';
                break;
                case self::UNDERFLOW :
                    $exception = 'UnderflowException';
                break;
                case self::UNEXPECTED_VALUE :
                    $exception = 'UnexpectedValueException';
                break;
                default :
                    throw new \Exception($message, $code);  
            }
            
            $exception = 'ABC\Abc\Core\Exception\\'. $exception;
            throw new $exception($message, $code, $file, $line);
        }
    } 
    
    /**
    * 
    */ 
    public static function badFunctionCall($message)
    {
        self::$marker = self::BAD_FUNCTION_CALL;
        self::error($message);
    }
    
    /**
    * 
    */ 
    public static function badMethodCall($message)
    {
        self::$marker = self::BAD_METHOD_CALL;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function domain($message)
    {
        self::$marker = self::DOMAIN;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function invalidArgument($message)
    {
        self::$marker = self::INVALID_ARGUMENT;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function length($message)
    {
        self::$marker = self::LENGTH;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function logic($message)
    {
        self::$marker = self::LOGIC;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function outOfBounds($message)
    {
        self::$marker = self::OUT_OF_BOUNDS;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function outOfRange($message)
    {
        self::$marker = self::OUT_OF_RANGE;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function overflow($message)
    {
        self::$marker = self::OVERFLOW;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function range($message)
    {
        self::$marker = self::RANGE;
        self::error($message);
    }    
    
    /**
    * 
    */ 
    public static function runtime($message)
    {
        self::$marker = self::RUNTIME;
        self::error($message);
    }        
    
    /**
    * 
    */ 
    public static function underflow($message)
    {
        self::$marker = self::UNDERFLOW;
        self::error($message);
    }        
    
    /**
    * 
    */ 
    public static function unexpectedValue($message)
    {
        self::$marker = self::UNEXPECTED_VALUE;
        self::error($message);
    }
    /**
    * 
    */ 
    public static function error($message)
    {
        trigger_error(self::$marker . $message, E_USER_WARNING);
    }    
}