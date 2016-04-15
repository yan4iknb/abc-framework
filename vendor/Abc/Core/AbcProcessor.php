<?php

namespace ABC\Abc\Core;

/**
* @TODO To clean in release 
*/
use ABC\Abc\Core\Debugger\Dbg;

    function dbg($var = 'stop')
    {   
        new Dbg($var);
    }



use ABC\Abc\Core\AbcConstants;
use ABC\Abc\Core\Configurator;
use ABC\Abc\Core\Container;

use ABC\Abc\Core\Exception\AbcException;
use ABC\Abc\Core\Exception\Error500Exception;
use ABC\Abc\Core\Debugger\Php\PhpHandler;

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
    * @var Container
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
        mb_internal_encoding('UTF-8');
        AbcConstants::set(); 
        $configurator = new Configurator;
        $this->config = $configurator->getConfig($appConfig, $siteConfig);
        $this->selectErrorMode();
        include_once 'Functions/default.php';
        $this->container = new Container;
        $this->setInStorage('config', $this->config);
        $this->setInContainer('Router');
        $this->setInContainer('Request');        
        $this->setInContainer('RoutesParser');
        $this->setInContainer('BaseTemplate');
        $this->setInContainer('AppManager');
        $this->setInContainer('Url');
        $this->setInStorage('Abc', $this);
    }
    
    /**
    * Запускает роутер 
    *
    * @return void
    */     
    public function startApplication()
    {
        $manager = $this->container->get('AppManager');
        $manager->run();
    }
    
    
    /**
    * Помещает объекты ядра в контейнер
    *
    * @param string $className
    *
    * @return void
    */     
    public function setInContainer($className)
    { 
        $container = $this->container;
        $this->container->set($className, 
               function() use ($className, $container) {
                   $className = 'ABC\Abc\Core\\' . $className;
                   return new $className($container);
               });
    }
    
    /**
    * Помещает данные в глобальное хранилище
    *
    * @param string $id
    * @param mix $data
    *
    * @return void
    */     
    public function setInStorage($id, $data)
    {  
        $this->container->setGlobal($id, 
                           function() use ($data) {
                               return $data;
                           });
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
    * Получает данные из глобального хранилища
    *
    * @param string $id
    *
    * @return mix
    */     
    public function getFromContainer($id = null)
    {  
        return $this->container->get($id);
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
            trigger_error(ABC_INVALID_ARGUMENT_EX . 
                          ABC_INVALID_SERVICE_NAME,
                          E_USER_WARNING);
        }
        
        $builder = '\ABC\Abc\Builders\\'. $service .'Builder';
         
        if (!class_exists($builder)) {
            trigger_error(ABC_BAD_FUNCTION_CALL_EX . 
                         $service . ABC_NO_SERVICE, 
                         E_USER_WARNING);
        }    
        
        $builder = new $builder;
        $builder->config  = $this->config;
        $builder->container = $this->container;
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
         
            if (isset($this->config['error_language'])) {
                $langusge = '\ABC\Abc\Resourses\Lang\\'. $this->config['error_language'];
                
                if (class_exists($langusge)) {
                    $langusge::set();
                } else {
                    \ABC\Abc\Resourses\Lang\En::set();
                }
                
            } else {
                \ABC\Abc\Resourses\Lang\En::set();
            }
         
            if ($this->config['error_mod'] === 'debug') {  
                new PhpHandler($this->config);
            } elseif ($this->config['error_mod'] === 'exception') {
                new AbcException($this->config);
            } else {
                throw new \Exception(ABC_INVALID_DEBUG_SETTING); 
            }
            
        } else {
            \ABC\Abc\Resourses\Lang\En::set();
            set_error_handler([$this, 'throwError500Exception']);
        }
    }
    
    /**
    * Бросает исключение на отчеты интерпретатора при включеной
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

