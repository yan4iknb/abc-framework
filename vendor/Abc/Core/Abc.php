<?php

namespace ABC\Abc\Core;


use ABC\Abc\Core\AbcConfigurator;
use ABC\Abc\Core\Exception\AbcError;
use ABC\Abc\Services\Builder;
use ABC\Abc\Services\Container\Container;

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
        $config = $configurator->getConfig();        
        $this->container = new Container; 
        $this->addToStorage('Abc', $this); 
        $this->addToStorage('config', $config);
        $this->addToContainer('AppManager');       
        $this->addToContainer('Request');
        $this->includeFunction();
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
    * Возвращает текущий контейнер
    *
    * @return object
    */     
    public function getContainer()
    {  
        return $this->container;
    }
    
    /**
    * Возвращает новый контейнер
    *
    * @return object
    */     
    public function getNewContainer()
    {  
        return new Container;
    }
    
    /**
    * Помещает любые данные в глобальное хранилище
    *
    * @param string $id
    * @param mix $data
    *
    * @return void
    */     
    public function addToStorage($id, $data)
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
    protected function addToContainer($className, $dir = '')
    { 
        $abc = $this;
        $this->container->setAsShared($className, 
               function() use ($className, $dir, $abc) {
                   $className = 'ABC\Abc\Core\\'. $dir . $className;
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
