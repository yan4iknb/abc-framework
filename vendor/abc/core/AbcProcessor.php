<?php

namespace ABC\Abc\Core;

use ABC\Abc\Core\ServiceLocator;
use ABC\Abc\Core\Router;

use ABC\Abc\Core\Debugger\DebugException;
use ABC\Abc\Core\Debugger\Error500Exception;
use ABC\Abc\Core\Debugger\Php\PhpHandler;
use ABC\Abc\Core\Debugger\Loger\Loger;

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
    protected $config;

    /**
    * @var ServiceLocator
    */ 
    protected $locator; 
    
    /**
    * @var object
    */ 
    protected $container;
    
    /**
    * @var Router
    */ 
    protected $router;
    
    /**
    * Конструктор
    * 
    * @param array $appConfig
    * @param array $siteConfig
    */    
    public function __construct($appConfig = [], $siteConfig = [])
    {
        $this->selectErrorMode();    
        $configurator  = new Configurator;
        $this->config  = $configurator->getConfig($appConfig, $siteConfig);
        $this->locator = new ServiceLocator;        
    }
    
    /**
    * Устанавливает пользовательские маршруты и запускает роутер 
    *
    * @return void
    */     
    public function route()
    { 
        $this->router  = new Router;
        $this->router->config = $this->config;
        $this->router->run();
    }
    
    /**
    * Выбирает и запускает сервис
    *
    * @return object
    */     
    public function getService($service = null)
    {    
        if (empty($service) || !is_string($service)) {
            throw new \InvalidArgumentException('Service name should be a string', E_USER_WARNING);
        }
        
        $builder = '\ABC\abc\builders\\'. $service .'Builder';
        
        if (!class_exists($builder)) {
            throw new \BadFunctionCallException('Service "'. $service .'" is not defined.', E_USER_WARNING);
        }    
        
        $builder = new $builder;
        $builder->config  = $this->config;
        $builder->locator = $this->locator;
        return $builder->get($service);
    }
  
    /**
    * Выбирает режим обработки ошибок
    *
    * @return void
    */     
    protected function selectErrorMode()
    {
        if (empty($this->config)) {
            set_error_handler([$this, 'throwDebugException']);        
            new PhpHandler();
        }
     
        if (!isset($this->config['debug_mod'])) {
            return;
        }
     
        if ($this->config['debug_mod'] === 'display') {
            set_error_handler([$this, 'throwDebugException']);        
            new PhpHandler($this->config);
        } elseif ($this->config['debug_mod'] === 'log')  {
            new Loger();
            set_error_handler([$this, 'throwError500Exception']);
        } elseif ($this->config['debug_mod'] == 500) {
            set_error_handler([$this, 'throwError500Exception']);
        }
    }
    
    /**
    * Бросает исключение на отчеты интерпретатора при вклченной
    * опции 500 Internal Server Error
    *
    * @return void
    */
    public function throwError500Exception($code, $message, $file, $line)
    { 
        if (error_reporting() & $code) {
            throw new Error500Exception($message, $code, $file, $line);
        }
    } 
    
    /**
    * Бросает исключение на trigger_eror и отчеты интерпретатора
    *
    * @return void
    */
    public function throwDebugException($code, $message, $file, $line)
    { 
        if (error_reporting() & $code) {
            throw new DebugException($message, $code, $file, $line);
        }
    }
}
