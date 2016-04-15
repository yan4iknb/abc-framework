<?php

namespace ABC;

use ABC\Abc\Core\Debugger\Dbg;
use ABC\Abc\Core\AbcProcessor;

/** 
 * Класс Abc 
 * Стартует фреймворк
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
 
class Abc
{
    
    /**
    * @var Abc 
    */
    protected static $abc; 
    
    /**
    * @var AbcProcess
    */
    protected $process;     

    /**
    * @var config 
    */
    protected $config;

    protected $autoload = '/Core/Autoloader.php';

    /**
    * Запуск фреймворка
    *
    * Допускает инициализацию только одного объекта
    *
    * Принимает аргументaми массивы пользовательских настроек.
    * Список настроек доступен в документации 
    *
    * @param array $appConfig
    * @param array $siteConfig
    *
    * @return void
    */     
    public static function createApp($appConfig = [], $siteConfig = [])
    { 
        if (!empty(self::$abc)) {
            throw new \LogicException('Only one process');  
        }
        
        self::$abc = new self;
        self::$abc->run($appConfig, $siteConfig);
    }
 
    /**
    * Формирует настройки, подключает автолоадер классов,
    * запускает фреймворк и приложение
    *
    * @param array $appConfig
    * @param array $siteConfig
    *
    * @return void
    */    
    protected function run($appConfig, $siteConfig)
    {
        $this->autoload = __DIR__ . $this->autoload;
        $this->autoloadSelector();
        self::$abc->process = new AbcProcessor($appConfig, $siteConfig);
        self::$abc->process->startApplication();
    }
    
    /**
    * Селектор выбора автозагрузчика
    *
    * Если в конфиге установлена настройка "composer", то подключит
    * автозагрузчик композера
    *
    * Если в настройке autoload_path указан другой автозагрузчик, то установит его.
    *
    * Приоритет у настройки "composer"
    * 
    * @return void
    */    
    protected function autoloadSelector()
    {
        if (empty($this->config['composer']) && !empty($this->config['autoload_path'])) {        
            $this->autoload = $this->config['autoload_path'];
            
        } elseif (!empty($this->config['composer'])) {         
            $this->autoload = __DIR__ .'/../autoload.php';
        }
        
        $this->autoloadInclude();
    }
 
    /**
    * Подключает автолоадер
    *
    * @return void
    */    
    protected function autoloadInclude()
    {
        include $this->autoload;
    } 
  
    /**
    * Возвращает объект фреймворка
    *
    * @return object
    */     
    public static function process()
    {
        return self::$abc->process;
    }
    
    /**
    * Возвращает объект сервиса
    *
    * @param string $service
    *
    * @return object
    */     
    public static function getService($service = null)
    {
        return self::$abc->process->getService($service);
    }
    
    /**
    * Инициализирует новый объект сервиса
    *
    * @param string $service
    *
    * @return object
    */ 
    public static function newService($service = null)
    {
        return self::$abc->process->newService($service);
    }

    /**
    * Помещает данные в глобальное хранилище
    *
    * @param string $id
    * @param mix $data
    *
    * @return void
    */     
    public static function setInStorage($id, $data)
    {
        self::$abc->process->setInStorage($id, $data);
    }    

    /**
    * Получает данные из глобального хранилища
    *
    * @param string $id
    *
    * @return mix
    */     
    public static function getFromStorage($id = null)
    {
        return self::$abc->process->getFromContainer($id);
    }
    
    /**
    * Инициализация GET параметра
    *
    * @return string
    */     
    public static function GET($key = null, $default = null)
    {
        return self::getService('Request')->iniGET($key, $default);
    }
    
    /**
    * Инициализация POST параметра
    *
    * @return string
    */     
    public static function POST($key = null, $default = null)
    {
        return self::getService('Request')->iniPOST($key, $default);
    }
    
    /**
    * Получает настройку конфигурации
    *
    * @return string
    */     
    public static function getConfig($key = null)
    {
        return self::$abc->process->getFromStorage('config')[$key];
    }
}


