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
    protected static $autoload = '/Autoloader.php';    

    protected static $abc; 
    protected static $process;     
    protected static $config;

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
            throw new \LogicException('Only one process');  
        }
        
        self::$config = array_merge($appConfig, $siteConfig);
        self::$autoload = __DIR__ . self::$autoload;
        self::autoloadSelector();
        self::$process = new AbcProcessor($appConfig, $siteConfig);
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
    * Возвращает объект сервиса
    *
    * @param string $service
    *
    * @return object
    */     
    public static function getService($service = null)
    {
        return self::$process->getService($service);
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
        return self::$process->newService($service);
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
    * @return string
    */     
    public static function GET($key = null, $default = null)
    {
        return self::$process->getFromStorage('Request')->iniGET($key, $default);
    }
    
    /**
    * Инициализация POST параметра
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
    * @return string
    */     
    public static function getConfig($key = null)
    {
        return self::$process->getFromStorage('config')[$key];
    }
}


