<?php

namespace ABC;

/** 
 * Класс Abc 
 * Стартует фреймворк
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 * @package system.core 
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
    protected $framework;     

/**
 * @var array 
 */
    protected $config;

/**
 * @var string
 */
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
 * @return object
 */     
    public static function createNew($appConfig = [], $siteConfig = [])
    {
        if (!empty(self::$abc)) {
            throw new \Exception('Only one object');  
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
 * Возвращает объект фреймворка
 *
 * @return object
 */     
    public static function current()
    {
        return self::$abc->framework;
    }    

/**
 * Возвращает текущие настройки
 *
 * @return array
 */    
    public function getConfig()
    {
        return $this->config;
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
        
        if (!empty($this->config['debug_mod'])) {
            $this->errorSelector();
        } 
        
        self::$abc->framework = new ABC\core\AbcFramework;
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
 * Выбор способа реакции на ошибку
 *
 * Принимает параметрами сообщение об ошибке и её уровень 
 *
 * @param string $message
 * @param int $errorLevel
 *
 * @return void
 */     
    protected function errorSelector()
    {
        new \ABC\abc\core\ErrorSelector($this->config);  
    }

/**
 * Возвращает установленную версию фреймворка
 * и его компонентов
 *
 * @return string
 */    
    final public static function getVersion($component = '')
    {
        if (!empty($component) && !is_string($component)) {
            throw new \Exception('Invalid argument. Expects parameter 1 to be string', E_USER_WARNING);
        }
        
        $version = (new \ABC\abc\components\ComponentRegistry)->getVersion($component);
        
        if (!empty($version)) {
            throw new \Exception("Component <b>$component</b> is not installed.", E_USER_WARNING);
        }
        
        return $version;  
    }
}








    
