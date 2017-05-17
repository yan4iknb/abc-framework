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
 
class ABC
{
    /**
    * Сервисы
    */
    const BB_DECODER    = 'BbDecoder';
    const CONTAINER     = 'Container'; 
    const PAGINATOR     = 'Paginator';    
    const HTTP          = 'Http';
    const PARAMS        = 'Params';    
    const DB_COMMAND    = 'DbCommand';    
    const MYSQLI        = 'Mysqli';    
    const PDO           = 'Pdo';
    const SQL_DEBUG     = 'SqlDebug';
    const STORAGE       = 'Storage';
    const TEMPLATE      = 'Template';
    const TPL_NATIVE    = 'TplNative';
    const URI_MANAGER   = 'UriManager';
    
    /**
    * Система
    */
    const ROUTER            = 'Router';
    const CALLABLE_RESOLVER = 'CallableResolver';
    const REQUEST           = 'Request';
    const RESPONSE          = 'Response';
    
    protected static $autoload = '/Autoloader.php';
    protected static $config;
    protected static $process;     

    /**
    * Старт приложения
    *
    * Принимает аргументaми массивы пользовательских настроек.
    * Список настроек доступен в документации abc-framework.ru/docs/setting
    *
    * @param array $appConfig
    * @param array $siteConfig
    *
    * @return object
    */     
    public static function startApp($appConfig = [], $siteConfig = [])
    { 
        if (empty(self::$process)) {
            self::process($appConfig, $siteConfig);        
        }
        
        return self::$process->startApp();   
    }
    
    /**
    * Старт роутинга
    *
    * Принимает аргументaми массивы пользовательских настроек.
    * Список настроек доступен в документации abc-framework.ru/docs/setting
    *
    * @param array $appConfig
    * @param array $siteConfig
    *
    * @return object
    */     
    public static function Router($appConfig = [], $siteConfig = [])
    { 
        if (empty(self::$process)) {
            self::process($appConfig, $siteConfig);        
        }
        
        return self::$process->router();   
    }
    
    /**
    * Запуск фреймворка с внешним роутингом
    *
    * @return void
    */     
    public static function run()
    { 
        self::$process->run();  
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
    * Получает текущий контейнер
    *
    * @return object
    */ 
    public static function getContainer()
    {
        return self::$process->getContainer();
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
    * Инициализация GET параметра
    *
    * @param string $key
    * @param string $default
    *
    * @return string
    */     
    public static function GET($key = null, $default = null)
    {
        return self::$process->getFromSystem('Request')->GET($key, $default);
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
        return self::$process->getFromSystem('Request')->POST($key, $default);
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
    
    /**
    * Запуск фреймворка
    *
    * Принимает аргументaми массивы пользовательских настроек.
    * Список настроек доступен в документации abc-framework.ru/docs/setting
    *
    * @param array $appConfig
    * @param array $siteConfig
    */     
    protected static function process($appConfig, $siteConfig)
    { 
        self::$config = array_merge($appConfig, $siteConfig);
        self::$autoload = __DIR__ . self::$autoload;
        self::autoloadSelector();
        self::$process = new \ABC\Abc\Core\Abc($appConfig, $siteConfig);  
    }
}


