<?php

namespace ABC\Abc\Core;

use ABC\Abc\Core\AbcConfigurator;

use ABC\Abc\Components\Builder;
use ABC\Abc\Components\Container\Container;

use ABC\Abc\Core\Exception\AbcError;
use ABC\Abc\Components\Debugger\Trace\ErrorHandler;

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
    
    public $debugReport = null;
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
        if (isset($this->config['abc_debug'])) {
          
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
            
            if (true === $this->config['abc_debug']) {  
                new ErrorHandler($this);
            } elseif (false === $this->config['abc_debug']) {
                new AbcError(true);
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
    * Возвращает контейнер
    *
    * @return object
    */     
    public function getContainer()
    {  
        return $this->container;
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
        $this->container->setAsShared($id, 
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
    * @param string $serviceId
    *
    * @return object
    */     
    public function newService($serviceId = null)
    {   
        $builder = $this->getBuilder($serviceId);
        return $builder->newService($serviceId);
    }
    
    /**
    * Выбирает и запускает синглтон сервиса
    *
    * @param string $serviceId
    *
    * @return object
    */     
    public function sharedService($serviceId = null)
    {  
        $builder = $this->getBuilder($serviceId);
        return $builder->sharedService($serviceId);
    }
    
    /**
    * Получает настройку конфигурации
    *
    * @param string $key
    *
    * @return string
    */     
    public function getConfig($key = null)
    {
        $config = $this->container->get('config');
     
        if (empty($key)) {
            return $config;
        } elseif (!is_string($key)) {
            AbcError::invalidArgument(ABC_INVALID_CONFIGURE);        
        } elseif (empty($config[$key])) {
            AbcError::invalidArgument('<strong>'. $key .'</strong>'. ABC_NO_CONFIGURE);
        }
            return $config[$key];
    }
  
    /**
    * Возвращает объект билдера
    *
    * @param string $serviceId
    *
    * @return object
    */     
    protected function getBuilder($serviceId = null)
    {    
        if (empty($serviceId) || !is_string($serviceId)) {
            AbcError::invalidArgument(ABC_INVALID_SERVICE_NAME);
        } 
        
        $builder = new Builder($serviceId, $this);
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
        $this->container->setAsShared($className, 
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
        include_once __DIR__ .'/Functions/default.php';
        abcForFunctions($this);
    }
    
}

