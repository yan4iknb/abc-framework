<?php

namespace ABC\Abc\Core;

use ABC\Abc\Resourses\Settings;
use ABC\Abc\Core\Exception\AbcError;
use ABC\Abc\Core\Exception\AbcError500Exception;
use ABC\Abc\Core\Exception\Error500Exception;

/** 
 * Конфигуратор
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
class AbcConfigurator
{
    /**
    * @var array
    */ 
    protected $config;
    protected $contentEnable = true;
    
    public function __construct($appConfig = [], $siteConfig = [])
    {    
        defined('ABC_DS') or define('ABC_DS', DIRECTORY_SEPARATOR);
        mb_internal_encoding('UTF-8');
        $this->setConfig($appConfig, $siteConfig);
        $this->setError500mode();  
    }
   
    /**
    * Устанавливает настрйки фреймворка
    *
    * @return array
    */     
    public function setConfig($appConfig, $siteConfig)
    {   
        if (!is_array($appConfig)) {
            AbcError::error('Application\'s configuration must be an array');
        }
        
        if (!is_array($siteConfig)) {
            AbcError::error('Site configuration must be an array');
        }
     
        $this->config = array_replace_recursive($appConfig, $siteConfig);
        $this->config = $this->normaliseConfig($this->config);
        $settings = Settings::get();
        $this->config = array_replace_recursive($settings, $this->config);
    } 
    
    /**
    * Устанавливает настрйки фреймворка
    *
    * @return array
    */     
    public function getConfig()
    {   
        $hardConfig = ['route_rules'      => $this->getRouteRule(),
                       'content_enable'   => $this->contentEnable
                  ];
        
        return array_merge($this->config, $hardConfig);
    }    
   
    /**
    * Устанавливает режим обработки ошибок
    *
    * @return void
    */     
    protected function setError500mode()
    {
        if (false === $this->config['abc_500']) {
            throw new \ErrorException('500', 500);
        } else {
            //set_error_handler([$this, 'throwError500Exception']);
        }    
    }

    /**
    * Возвращает массив маршрутов 
    *
    * @return array
    */     
    public function getRouteRule()
    { 
        if (!isset($this->config['route_rules'])) {
            return [];
        }
        
        if (is_array($this->config['route_rules'])) {
            return $this->config['route_rules'];
        }
        
        if (is_file($this->config['route_rules'])) {
            return $this->parseConfigRoutes($this->config['route_rules']);
        }
        
        AbcError::badFunctionCall(ABC_UNKNOWN_ROUTES);
    }  

    /**
    * Приводит все ключи к нижнему регистру 
    *
    * @return array
    */     
    protected function normaliseConfig($config)
    { 
        $config = array_change_key_case($config); 
        
        foreach ($config as $key => $array) { 
            if (is_array($array)) { 
                $config[$key] = $this->normaliseConfig($array); 
            } 
        } 
        return $config; 
    }  
    
    /**
    * Разбирает конфигурационный файл маршрутов и возвращает массив 
    *
    * @param string $file
    *
    * @return array
    */     
    protected function parseConfigRoutes($file)
    { 
        $routeRule = file_get_contents($file);
        // To be continued
        return $routeRule;
    } 

    /**
    * Бросает исключение на отчеты интерпретатора при включеной
    * опции 500 Internal Server Error
    *
    * @return void
    */
    public function throwError500Exception($code, $message, $file, $line)
    {
        if (error_reporting() & $code) {
            $this->contentEnable = false;
            throw new AbcError500Exception($message, $code, $file, $line);
        }
    } 
}






