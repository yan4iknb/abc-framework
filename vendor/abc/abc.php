<?php
namespace ABC;

use Exception;

/**
 * Текущая версия фреймворка.
 */
define('ABC_VERSION', '1.0.0');

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
 * @var string
 */
    public $autoload = __DIR__ .'/core/ABCAutoloader.php'; 
    
/**
 * @var object
 */
    protected static $abc;    

/**
 * @var array 
 */
    protected $config;

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
    public static function createNewAbc($appConfig = [],  $siteConfig = [])
    {
        if (!empty(self::$abc)) {
            throw new Exception('Only one object');  
        }
       
        if(!is_array($appConfig))
            throw new Exception('Application configuration is not an array');
     
        if(!is_array($siteConfig))
            throw new Exception('Site configuration is not an array');
            
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
        return self::$abc;
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
        $this->autoloadIclude();
        
        if (!empty($this->config['debug_mod'])) {
            $this->errorSelector();
        }  
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
    protected function errorSelector($message = '', $errorLevel = '')
    {
        $selector = new \ABC\abc\core\ErrorSelector($this->config);
        $selector->setMessage($message);
        $selector->setErrorLevel($errorLevel);
        $selector->selectErrorMode();
    }

/**
 * Возвращает установленную версию фреймворка
 * и его компонентов
 *
 * @return string
 */    
    final public static function getVersion($component = '')
    {
        if (!empty($component) && is_string($component)) {
            return (new \ABC\resourse\AbcComponents)->getVersion($component);  
        }
        
        return ABC_VERSION;
    }
}








    