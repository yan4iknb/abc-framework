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
             
                case ABC_BAD_FUNCTION_CALL_EX :
                    $exception = 'BadFunctionCallException';
                break;
                case ABC_BAD_METHOD_CALL_EX :
                    $exception = 'BadMethodCallException';
                break;
                case ABC_DOMAIN_EX :
                    $exception = 'DomainException';
                break;
                case ABC_INVALID_ARGUMENT_EX :
                    $exception = 'InvalidArgumentException';
                break;
                case ABC_LENGTH_EX :
                    $exception = 'LengthException';
                break;
                case ABC_LOGIC_EX :
                    $exception = 'LogicException';
                break;
                case ABC_OUT_OF_BOUNDS_EX :
                    $exception = 'OutOfBoundsException';
                break;
                case ABC_OUT_OF_RANGE_EX :
                    $exception = 'OutOfRangeException';
                break;
                case ABC_OVERFLOW_EX :
                    $exception = 'OverflowException';
                break;
                case  ABC_RANGE_EX:
                    $exception = 'RangeException';
                break;
                case  ABC_RUNTIME_EX:
                    $exception = 'RuntimeException';
                break;
                case ABC_UNDERFLOW_EX :
                    $exception = 'UnderflowException';
                break;
                case ABC_UNEXPECTED_VALUE_EX :
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
