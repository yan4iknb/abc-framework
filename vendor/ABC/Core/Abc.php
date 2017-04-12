<?php

namespace ABC\ABC\Core;

use ABC\ABC\Core\AbcConfigurator;
use ABC\ABC\Core\Base;
use ABC\ABC\Core\Exception\AbcError;
use ABC\ABC\Core\Routing\AppManager;
use ABC\ABC\Core\Routing\CallableResolver;
use ABC\ABC\Core\Routing\Router;
use ABC\ABC\Services\Builder;
use ABC\ABC\Services\Container\Container;
use ABC\ABC\Services\Storage\Storage;



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
        $this->includeFunction();
    } 
    
    /**
    * Запускает приложение 
    *
    * @return void
    */     
    public function startApp()
    {
        $this->storage->add(\ABC\ABC::ROUTER, new Router($this));
        $manager = new AppManager($this);
        $manager->run();
    }
    
    /**
    * Внешний роутинг
    *
    * @return void
    */     
    public function router()
    { 
        $this->storage->add(\ABC\ABC::CALLABLE_RESOLVER, new CallableResolver($this));    
        return $this->storage->get(\ABC\ABC::CALLABLE_RESOLVER);   
    }
    
    /**
    * Запуск фреймворка с внешним роутингом
    *
    * @return void
    */     
    public function run()
    {
        $response = $this->storage->get(\ABC\ABC::RESPONSE);
     
        if (!empty($response)) {
            $this->sendHeaders($response);
            $size = $response->getBody()->getSize();
            
            if ($size !== null) {
                $response = $response->withHeader('Content-Length', (string)$size);
                $this->sendBody($response);
            }
            
        } else {
            $base = new Base;
            $base->abc = $this;
            $base->action404();
        }
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
    * Возвращает системное хранилище
    *
    * @return object
    */     
    public function getStorage()
    { 
        return $this->storage;
    }
    
    /**
    * Возвращает массив установленного окружения
    *
    * @return array
    */     
    public function getFromStorage($name, $key = null)
    {  
        if (!$this->storage->has($name)) {
            return false;
        }
        
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
    
    /**
    * Отправляет заголовки
    *
    * @param obj $response
    *
    * @return void
    */     
    protected function sendHeaders($response)
    { 
        if (!headers_sent()) {
            header(sprintf(
                'HTTP/%s %s %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ));
         
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header(sprintf('%s: %s', $name, $value), false);
                }
            }
        }
    }
    
    /**
     * Send the response the client
     *
     * @param ResponseInterface $response
     */
    public function sendBody($response)
    {
        $body = $response->getBody();
        
        if ($body->isSeekable()) {
            $body->rewind();
        }
        
        $chunkSize     = 4096;
        $contentLength = $response->getHeaderLine('Content-Length'); 
        $amountToRead  = $contentLength;

        while ($amountToRead > 0 && !$body->eof()) {
            $data = $body->read(min($chunkSize, $amountToRead));
            echo $data;
          
            $amountToRead -= strlen($data);
         
            if (connection_status() != CONNECTION_NORMAL) {
                break;
            }
        }

    }
}
