<?php

namespace ABC\Abc\Core;

use ABC\Abc\Core\AbcConfigurator;
use ABC\Abc\Core\Exception\AbcError;
use ABC\Abc\Components\Container\Container;
use ABC\Abc\Core\Debugger\Php\PhpHandler;

/** 
 * Класс AbcFramework
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class Abc
{
    /**
    * @var array
    */ 
    protected $config; 
    
    /**
    * @var ABC\Abc\Components\Container\Container
    */ 
    protected $container;
    
    /**
    * Конструктор
    * 
    * @param array $appConfig
    * @param array $siteConfig
    */    
    public function __construct($appConfig = [], $siteConfig = [])
    {         
        $configurator = new AbcConfigurator($appConfig, $siteConfig);
        $this->config = $configurator->getConfig();
        $this->container = new Container;
        $this->setToStorage('config', $this->config);
        $this->setToStorage('Abc', $this);
        $this->addToContainer('AppManager');       
        $this->addToContainer('Request');
        $this->addToContainer('Router');
        $this->addToContainer('Response');
        $this->includeFunction();
        $this->setErrorMode();
    }
 
    
    /**
    * Устанавливает режим обработки ошибок
    *
    * @return void
    */     
    protected function setErrorMode()
    {
        if (isset($this->config['abc_debugger'])) {
          
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
            
            if (true === $this->config['abc_debugger']) {  
                new PhpHandler($this);
            } elseif (false === $this->config['abc_debugger']) {
                new AbcError;
            } else {
                throw new \Exception(strip_tags(ABC_INVALID_DEBUG_SETTING)); 
            }
             
        }
    }    
   
    /**
    * Запускает приложение 
    *
    * @return void
    */     
    public function startApp()
    {
        $manager = $this->getFromStorage('AppManager');
        $manager->run();
    }
    
    /**
    * Помещает любые данные в глобальное хранилище
    *
    * @param string $id
    * @param mix $data
    *
    * @return void
    */     
    public function setToStorage($id, $data)
    {  
        $this->container->setGlobal($id, 
               function() use ($data) {
                   return $data;
               });
    }
    
    /**
    * Получает содержимое глобального хранилища по ключу
    *
    * @param string $id
    *
    * @return mix
    */     
    public function getFromStorage($id = null)
    {  
        return $this->container->get($id);
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
    protected function prepareBuilder($service = null)
    {    
        if (empty($service) || !is_string($service)) {
            AbcError::invalidArgument(INVALID_SERVICE_NAME);
        }
        
        $builder = '\ABC\Abc\Builders\\'. $service .'Builder';
         
        if (!class_exists($builder)) {
            AbcError::badFunctionCall(ABC_NO_SERVICE);
        }    
        
        $builder = new $builder;
        $builder->config  = $this->config;
        $builder->container = $this->container;
        return $builder;
    }
    
    /**
    * Помещает объекты ядра в контейнер
    *
    * @param string $className
    *
    * @return void
    */     
    protected function addToContainer($className)
    { 
        $abc = $this;
        $this->container->setGlobal($className, 
               function() use ($className, $abc) {
                   $className = 'ABC\Abc\Core\\' . $className;
                   return new $className($abc);
               });
    }
    
    /**
    * Подключает файл функций 
    *
    * @return void
    */     
    protected function includeFunction()
    {
        include_once 'Functions/default.php';
        abcForFunctions($this);
    }
    
}

