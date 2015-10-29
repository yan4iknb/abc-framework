<?php

namespace ABC;


use ABC\abc\core\AbcProcessor;
use ABC\abc\core\debugger\Dbg;

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
    * Формирует настройки и подключает автолоадер классов.
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
        self::$abc->process->route();
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
        
        $this->autoloadIclude();
    }
 
    /**
    * Подключает автолоадер
    *
    * @return void
    */    
    protected function autoloadIclude()
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
    * @return object
    */     
    public static function getService($service = null)
    {
        return self::$abc->process->getService($service);
    }    
    
    /**
    * Алиас метода getService()
    *
    * @return object
    */     
    public static function gs($service = null)
    {
        return self::getService($service);
    }
    
    /**
    * Инициализирует новый объект сервиса
    *
    * @return object
    */     
    public static function newService($service = null)
    {
        return self::$abc->process->newService($service);
    }    
    
    /**
    * Алиас метода newService()
    *
    * @return object
    */     
    public static function ns($service = null)
    {
        return self::newService($service);
    }     
    /**
    * Метод трассировки скриптов
    *
    * @return void
    */ 
    public static function dbg($var = 'stop')
    {   
        new Dbg($var);
    }
}
