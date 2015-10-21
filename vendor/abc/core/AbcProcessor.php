<?php

namespace ABC\abc\core;

use ABC\abc\core\ServiceLocator;

use ABC\abc\core\debugger\ErrorException;
use ABC\abc\core\debugger\php\PhpHandler;
use ABC\abc\core\debugger\loger\Loger;

/** 
 * Класс AbcFramework
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */   
class AbcProcessor
{
    /**
    * @var array
    */ 
    protected $userConfig;

    /**
    * @var ServiceLocator
    */ 
    protected $locator; 
    
    /**
    * @var object
    */ 
    protected $container;
    
    /**
    * Конструктор
    * 
    */    
    public function __construct($userConfig = [])
    {
        $this->userConfig = $userConfig;
        $this->selectErrorMode(); 
        $this->locator = new ServiceLocator;
    }
    
    /**
    * Выбирает режим обработки ошибок
    *
    * @return void
    */     
    protected function selectErrorMode()
    {
        if (empty($this->userConfig['debug_mod'])) {
            return false;
        } 
     
        if ($this->userConfig['debug_mod'] === 'display') {
            set_error_handler([$this, 'setException']);        
            new PhpHandler();
        } elseif ($this->userConfig['debug_mod'] === 'log')  {
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
    
    /**
    * Выбирает и запускает компонент
    *
    * @return object
    */     
    public function getService($service = null)
    {    
        if (empty($service) || !is_string($service)) {
            throw new \InvalidArgumentException('Service name should be a string', E_USER_WARNING);
        }
        
        $builder = '\ABC\abc\builders\\'. $service .'Builder';
        $builder = new $builder;
        $builder->userConfig = $this->userConfig;
        $builder->locator    = $this->locator;
        $object  = $builder->get($service);
        
        if (false === $object) {
            throw new \BadFunctionCallException('Service "'. $service .'" is not defined.', E_USER_WARNING);
        }
        
        return $object;
    }
}
