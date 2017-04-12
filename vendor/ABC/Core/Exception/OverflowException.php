<?php

namespace ABC\Abc\Core\Exception;

/** 
 * Класс OverflowException
 * Адаптирует trigger_error к Exception
 * для корректного выброса исключения
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */  

class OverflowException extends \OverflowException
{
    /**
    * Конструктор
    * 
    */  
    public function __construct($message, $code, $file, $line) 
    {
        $this->file = $file;
        $this->line = $line;        
        parent::__construct($message, $code);
    }
}  
