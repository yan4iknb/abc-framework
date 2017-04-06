<?php

namespace ABC\Abc\Core;


use ABC\Abc\Core\AbcConfigurator;
use ABC\Abc\Core\Exception\AbcError;
use ABC\Abc\Core\Routing\AppManager;
use ABC\Abc\Core\Routing\CallableResolver;
use ABC\Abc\Core\Routing\Router;
use ABC\Abc\Services\Builder;
use ABC\Abc\Services\Container\Container;
use ABC\Abc\Services\Storage\Storage;



/** 
 * Класс AbcFramework
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/ 
 */   
class Abc
{


    protected $storage;
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
        $this->storage   = new Storage;
        $this->storage->addArray($config, 'config');
        $this->storage->add('Router', new Router($config));
        $this->includeFunction();
    } 
    
    /**
    * Запускает приложение 
    *
    * @return void
    */     
    public function run()
    {
        $manager = new AppManager($this);
        $manager->run();
    }
    
    /**
    * Запуск фреймворка с роутингом
    *
    * @return void
    */     
    public function router()
    { 
        return new CallableResolver($this);    
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
        $builder   = $this->getBuilder($serviceId);
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
        $builder   = $this->getBuilder($serviceId);
        return $builder->sharedService();
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
    * Возвращает настройки конфигурации
    *
    * @param string $key
    *
    * @return array|string|bool
    */     
    public function getConfig($key = null, $default = null)
    {
        if (empty($key) && null !== $default) {
            return $default;
        } 
    
        if (null === $key) {
            return $this->storage->all('config');
        } 
        
        if (!is_string($key)) {
            AbcError::invalidArgument(ABC_INVALID_CONFIGURE);
            return false;
        } 
        
        if (!$this->storage->has($key, 'config')) {
            AbcError::invalidArgument('<strong>'. $key .'</strong>'. ABC_NO_CONFIGURE);
            return false;
        }
        
        return $this->storage->get($key, 'config');
    }
    
    /**
    * Возвращает массив установленного окружения
    *
    * @return array
    */     
    public function getFromStorage($name, $key = null)
    {    
        return $this->storage->get($name, $key);
    } 
    
    /**
    * Возвращает массив установленного окружения
    *
    * @return array
    */     
    public function getEnvironment()
    {    
        return $this->config['environment'];
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
    * Подключает файл функций 
    *
    * @return void
    */     
    protected function includeFunction()
    {
        include_once __DIR__ .'/functions.php';
        abcForFunctions($this);
    }   
}
