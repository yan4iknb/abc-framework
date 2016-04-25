<?php

namespace ABC;

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
    protected static $autoload = '/Autoloader.php';    

    protected static $config;
    protected static $process;     

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
    public static function startApp($appConfig = [], $siteConfig = [])
    { 
        if (!empty(self::$process)) {
            throw new \Exception('Only one process');  
        }
        
        self::$config = array_merge($appConfig, $siteConfig);
        self::$autoload = __DIR__ . self::$autoload;
        self::autoloadSelector();
        self::$process = new \ABC\Abc\Core\Abc($appConfig, $siteConfig);
        self::$process->startApp();    
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
    protected static function autoloadSelector()
    {
        if (empty(self::$config['composer']) && !empty(self::$config['autoload_path'])) {        
            self::$autoload = self::$config['autoload_path'];
            
        } elseif (!empty(self::$config['composer'])) {         
            self::$autoload = __DIR__ .'/../autoload.php';
        }
        
        self::autoloadInclude();
    }
 
    /**
    * Подключает автолоадер
    *
    * @return void
    */    
    protected static function autoloadInclude()
    {
        include self::$autoload;
        new \Autoloader(self::$config);
    } 
  
    /**
    * Возвращает объект фреймворка
    *
    * @return object
    */     
    public static function process()
    {
        return self::$process;
    }
   
    /**
    * Инициализирует новый объект сервиса
    *
    * @param string $serviceId
    *
    * @return object
    */ 
    public static function newService($serviceId = null)
    {
        return self::$process->newService($serviceId);
    }

    
    /**
    * Возвращает объект сервиса (singltone)
    *
    * @param string $serviceId
    *
    * @return object
    */     
    public static function sharedService($serviceId = null)
    {
        return self::$process->sharedService($serviceId);
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
        self::$process->setInStorage($id, $data);
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
        return self::$process->getFromStorage($id);
    }
    
    /**
    * Инициализация GET параметра
    *
    * @param string $key
    * @param string $default
    *
    * @return string
    */     
    public static function GET($key = null, $default = null)
    {
        return self::$process->getFromStorage('Request')->iniGET($key, $default);
    }
    
    /**
    * Инициализация POST параметра
    *
    * @param string $key
    * @param string $default
    *
    * @return string
    */     
    public static function POST($key = null, $default = null)
    {
        return self::$process->getFromStorage('Request')->iniPOST($key, $default);
    }
    
    /**
    * Получает настройку конфигурации
    *
    * @param string $key
    *
    * @return string
    */     
    public static function getConfig($key = null)
    {
        return self::$process->getConfig($key);
    }
}


