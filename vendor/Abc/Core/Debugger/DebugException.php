<?php

namespace ABC\Abc\Core\Debugger;

/** 
 * Класс DebugException 
 * Адаптирует trigger_error к Exception
 * для корректного выброса исключения
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  

class DebugException extends \Exception 
{

    /**
    * Меняет местами порядок аргументов, передаваемых trigger_error
    * для корректного выброса исключения
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
        $this->file = $file;
        $this->line = $line; 
        $message = 'перевод';
        parent::__construct($message, $code);
    }
}  