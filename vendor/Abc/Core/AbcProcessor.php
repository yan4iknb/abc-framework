<?php

namespace ABC\Abc\Core;

use ABC\Abc\Core\AbcConstants;
use ABC\Abc\Core\AbcConstants;
use ABC\Abc\Core\Configurator;
use ABC\Abc\Core\Router;

use ABC\Abc\Core\Exception\AbcException;
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
 * @license http://www.wtfpl.net/ 
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
        AbcConstants::set(); 
        $configurator  = new Configurator;
        $this->config  = $configurator->getConfig($appConfig, $siteConfig);
        $this->selectErrorMode();           
        $this->locator = new Container;        
    }
    
    /**
    * Запускает роутер 
    *
    * @return void
    */     
    public function route()
    { 
        $this->router = new Router;
        $this->router->config = $this->config;
        $this->router->run();
    }

    /**
    * Выбирает и запускает сервис
    *
    * @param string $service
    *
    * @return object
    */     
    public function newService($service = null)
    {   
        $builder = $this->prepareBuilder($service);
        return $builder->newService($service);
    }
    
    /**
    * Выбирает и запускает синглтон сервиса
    *
    * @param string $service
    *
    * @return object
    */     
    public function getService($service = null)
    {  
        $builder = $this->prepareBuilder($service);
        return $builder->getService($service);
    }

    /**
    * Подготовка билдера
    *
    * @param string $service
    *
    * @return object
    */     
    public function prepareBuilder($service = null)
    {    
        if (empty($service) || !is_string($service)) {
            trigger_error(ABC_INVALID_ARGUMENT_EX 
                         .'Service name should be a string',
                         E_USER_WARNING);
        }
        
        $builder = '\ABC\abc\builders\\'. $service .'Builder';
        
        if (!class_exists($builder)) {
            trigger_error(ABC_BAD_FUNCTION_CALL_EX 
                         .'Service "'. $service .'" is not defined.', 
                         E_USER_WARNING);
        }    
        
        $builder = new $builder;
        $builder->config  = $this->config;
        $builder->locator = $this->locator;
        return $builder;
    }
    
    
    /**
    * Выбирает режим обработки ошибок
    *
    * @return void
    */     
    protected function selectErrorMode()
    {
        if (isset($this->config['error_mod'])) {
         
            if ($this->config['error_mod'] === 'debug') {  
                new PhpHandler($this->config);
            } elseif ($this->config['error_mod'] === 'exception') {
                new AbcException($this->config);
            } elseif ($this->config['error_mod'] == 500) {
                set_error_handler([$this, 'throwError500Exception']);
            }
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
}
