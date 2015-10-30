<?php

namespace ABC\Abc\Core\Exception;

/** 
 * Класс DebugException 
 * Адаптирует trigger_error к Exception
 * для корректного выброса исключения
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */  

class Error500Exception extends \Exception 
{
    /**
    * Генерирует сзаголовок Error 500 Internal Server Error
    * на сообщения об ошибках  
    *
    * @param string $message
    * @param string $code
    * @param string $file 
    * @param string $line 
    *
    * @return void
    */     
    public function __construct($message, $code, $file, $line) 
    {
        header("HTTP/1.1 500 Internal Server Error");
        exit();
    }
}  