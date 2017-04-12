<?php

namespace ABC\Abc\Core;

use ABC\Abc\Resourses\Settings;
use ABC\Abc\Core\PhpBugsnare\Bugsnare;
use ABC\Abc\Core\Exception\AbcError;
use ABC\Abc\Core\Exception\AbcError500Exception;

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

    protected $config;
    
    public function __construct($appConfig = [], $siteConfig = [])
    {    
        defined('ABC_DS') or define('ABC_DS', DIRECTORY_SEPARATOR);
        mb_internal_encoding('UTF-8');
        $this->setConfig($appConfig, $siteConfig);
        error_reporting($this->config['errors']['error_reporting']);
        $this->setErrorLanguage();
        $this->setErrorMode(); 
    }
    
    /**
    * Устанавливает язык отчета об ошибках
    *
    * @return void
    */     
    protected function setErrorLanguage()
    {
        $langusge = '\ABC\Abc\Resourses\Lang\\'. $this->config['debug']['language'];
        
        if (class_exists($langusge)) {
            $langusge::set();
        } else {
            throw new \Exception($this->config['debug']['language'] 
                               .' language is not supported'
            );
        }
    } 
   
    /**
    * Устанавливает режим обработки ошибок
    *
    * @return void
    */     
    protected function setErrorMode()
    {
        new AbcError($this->config['debug']);        
       
        if (!empty($this->config['errors']['abc_500'])) {
            error_reporting($this->config['errors']['level_500']);
            ob_start(); 
            register_shutdown_function([$this, 'error500']);
        } elseif (!empty($this->config['debug']['bugsnare'])) {
            new Bugsnare($this->config['debug']);
        } 
    }  
    
    /**
    * Обработка ошибок с помощью страницы 500 Internal Server Error
    *
    * @return void
    */
    public function error500()
    {
        if ($error = error_get_last() AND $error['type'] & $this->config['errors']['level_500']) {
            ob_end_clean();
            throw new AbcError500Exception($this->config['errors']);
        } else {
            ob_flush();
        }
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
        $hardConfig['router'] = ['route_rules' => $this->getRouteRule()];
        return array_merge_recursive($this->config, $hardConfig);
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
         
            if (is_array($array) && $key !== 'environment') { 
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
}
