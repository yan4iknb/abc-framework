<?php

namespace ABC\Abc\Core\Exception;

/** 
 * Класс AbcException 
 * Адаптирует trigger_error к Exception
 * для корректного выброса исключения
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */  

class AbcException
{
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
             
                case Answer::BAD_FUNCTION_CALL :
                    $exception = 'BadFunctionCallException';
                break;
                case Answer::BAD_METHOD_CALL :
                    $exception = 'BadMethodCallException';
                break;
                case Answer::DOMAIN :
                    $exception = 'DomainException';
                break;
                case Answer::INVALID_ARGUMENT :
                    $exception = 'InvalidArgumentException';
                break;
                case Answer::LENGTH :
                    $exception = 'LengthException';
                break;
                case Answer::LOGIC :
                    $exception = 'LogicException';
                break;
                case Answer::OUT_OF_BOUNDS :
                    $exception = 'OutOfBoundsException';
                break;
                case Answer::OUT_OF_RANGE :
                    $exception = 'OutOfRangeException';
                break;
                case Answer::OVERFLOW :
                    $exception = 'OverflowException';
                break;
                case Answer::RANGE:
                    $exception = 'RangeException';
                break;
                case Answer::RUNTIME:
                    $exception = 'RuntimeException';
                break;
                case Answer::UNDERFLOW :
                    $exception = 'UnderflowException';
                break;
                case Answer::UNEXPECTED_VALUE :
                    $exception = 'UnexpectedValueException';
                break;
                default :
                    throw new \Exception($message, $code);  
            }
            
            $exception = 'ABC\Abc\Core\Exception\\'. $exception;
            throw new $exception($message, $code, $file, $line);
        }
    }  
}
