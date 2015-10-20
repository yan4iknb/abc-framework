<?php

namespace ABC\abc\core;

use ABC\abc\core\php\PhpHandler as PhpHandler;
use ABC\abc\core\loger\Loger as Loger; 

/** 
 * Класс ErrorSelector
 * Выбирает способ обработки ошибок
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */   
 
class ErrorSelector
{
    /**
    * $var array
    */     
   protected $config;  
  
    /**
    * Конструктор
    *
    * @param array $config
    */        
    public function __construct($config)
    {
        $this->config = $config;
        $this->selectErrorMode();
    }    
   
    /**
    * Выбирает режим обработки ошибок
    *
    * @return void
    */     
    public function selectErrorMode()
    {
        set_error_handler([$this, 'setException']);
     
        if ($this->config['debug_mod'] === 'display') {
            new PhpHandler();
        }elseif ($this->config['debug_mod'] === 'log')  {
            new Loger();
        }
    }
   
    /**
    * Бросает исключение на trigger_eror и отчеты интерпретатора
    *
    * @return void
    */
    public function setException($code, $message, $file, $line)
    { 
        if (error_reporting() & $code) {
            throw new ErrorException($message, $code, $file, $line);
        }
    }    
}

   