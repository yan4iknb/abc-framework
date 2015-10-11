<?php

namespace ABC\abc\core;

use ABC\abc\components\debuger\AbcException as AbcException;
use ABC\abc\components\debuger\php\PhpHandler as PhpHandler;
use ABC\abc\components\AbcProfiler as AbcProfiler; 

/** 
 * Класс ErrorSelector
 * Выбирает способ обработки ошибок
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 * @package system.core 
 */   
 
class ErrorSelector
{
/**
 * $var array
 */     
   protected $config;

 /**
 * $var string
 */     
   protected $message;
   
/**
 * $var int
 */     
   protected $errorLevel;   
   
   
/**
 * Конструктор
 *
 * @param array $config
 */        
    public function __construct($config)
    {
        $this->config = $config;
    }    

/**
 * Устанавливает сообщение об ошибке
 *
 * @param string $message
 *
 * @return void
 */        
    public function setMessage($message)
    {
        $this->message = $message;
    }
    
/**
 * Устанавливает уровень ошибки
 *
 * @param int $errorLevel
 *
 * @return void
 */      
    public function setErrorLevel($errorLevel)
    {
        $this->errorLevel = $errorLevel;
    }
    
/**
 * Выбирает режим обработки ошибок
 *
 * @return void
 */     
    public function selectErrorMode()
    {
        if (!empty($this->config['debug_mod'] === 'abc') ) {
            $this->setExceptionMod();
        }elseif ($this->config['debug_mod'] === 'profiling')  {
            (new AbcProfiler)->run($this->message, $this->errorLevel);
        }
    }
    
/**
 * Устанавливает ABC обработчик исключений
 *
 * @return void
 */     
    public function setExceptionMod()
    {
        set_error_handler([$this, 'setAbcException']);
        new PhpHandler($this->message, $this->errorLevel);
    } 
    
    
/**
 * Бросает исключение на trigger_eror и отчеты интерпретатора
 *
 * @return void
 */
    public function setAbcException($code, $message, $file, $line)
    { 
        if (error_reporting() & $code) {
            throw new AbcException($message, $code, $file, $line);
        }
    }    
}








    