<?php

namespace ABC\Abc\Core;

use ABC\Abc\Core\AbcConfigurator;
use ABC\Abc\Core\Container;

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
        $configurator = new AbcConfigurator($appConfig, $siteConfig);
        $this->config = $configurator->getConfig();
        $this->container = new Container;
        $this->setInStorage('config', $this->config);
        $this->setInContainer('AppManager');
        $this->setInContainer('BaseTemplate');        
        $this->saredInContainer('Request');
        $this->setInContainer('Router');
        $this->setInContainer('RoutesParser');
        $this->setInContainer('Url');
    }
    
    /**
    * Запускает приложение 
    *
    * @return void
    */     
    public function startApplication()
    {
        $manager = $this->container->get('AppManager');
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
    public function setInStorage($id, $data)
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
            Response::invalidArgumentError(INVALID_SERVICE_NAME);
        }
        
        $builder = '\ABC\Abc\Builders\\'. $service .'Builder';
         
        if (!class_exists($builder)) {
            Response::badFunctionCallError(ABC_NO_SERVICE);
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
    protected function setInContainer($className)
    { 
        $container = $this->container;
        $this->container->set($className, 
               function() use ($className, $container) {
                   $className = 'ABC\Abc\Core\\' . $className;
                   return new $className($container);
               });
    }
    
    /**
    * Помещает объекты ядра в контейнер по принципу Singletone
    *
    * @param string $className
    *
    * @return void
    */     
    protected function saredInContainer($className)
    { 
        $container = $this->container;
        $this->container->setGlobal($className, 
               function() use ($className, $container) {
                   $className = 'ABC\Abc\Core\\' . $className;
                   return new $className($container);
               });
    }
}

