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
 * @license http://abc-framework.com/license/ 
 */   
 
class Abc
{
    
    /**
    * @var object
    */
    protected static $abc; 
    
    /**
    * @var object
    */
    protected $process;     

    /**
    * @var array 
    */
    protected $config;

    protected $autoload = __DIR__ .'/core/Autoloader.php';

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
            throw new \Exception('Only one process');  
        }
       
        if (!is_array($appConfig)) {
            throw new \Exception('Configuring the application is to be performed array');
        }
        
        if (!is_array($siteConfig)) {
            throw new \Exception('Configuring the site is to be performed array');
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
        $this->config = array_merge($appConfig, $siteConfig); 
        $this->autoloadSelector();
        self::$abc->process = new AbcProcessor($this->config);
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
    * Возвращает объект фреймворка
    *
    * @return object
    */     
    public static function component($component = null)
    {
        return self::$abc->process->getComponent($component);
    }    
    
    /**
    * Перезаписывает  компонент
    *
    * @param string $component
    * @param array $data
    *
    * @return object
    */      
    public static function newComponent($component = null, $data = [])
    {    
        return self::$abc->process->newComponent($component, $data);
    }
    
    /**
    * Перезаписывает глобальный компонент
    *
    * @param string $component
    * @param array $data
    *
    * @return object
    */     
    public static function newGlobalComponent($component = null, $data = [])
    {    
        return self::$abc->process->newGlobalComponent($component, $data);
    } 
    
    /**
    * Метод трассировки скриптов
    *
    * @return void
    */ 
    public static function dbg($var = 'stop', $no = null)
    {   
        new Dbg($var, $no);
    }
}








    
