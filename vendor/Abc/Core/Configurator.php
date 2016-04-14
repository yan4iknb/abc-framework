<?php

namespace ABC\Abc\Core;

use ABC\Abc\Resourses\Settings;

/** 
 * Конфигуратор
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
class Configurator
{
    /**
    * @var array
    */ 
    protected $config;
    
    /**
    * Возвращает массив пользовательских настроек 
    *
    * @return array
    */     
    public function getConfig($appConfig, $siteConfig)
    {   
        if (!is_array($appConfig)) {
            trigger_error(ABC_INVALID_ARGUMENT_EX .
                          ABC_INVALID_CONFIGURE,
                          E_USER_WARNING);
        }
        
        if (!is_array($siteConfig)) {
            trigger_error(ABC_INVALID_ARGUMENT_EX .
                          ABC_INVALID_CONFIGURE_SITE,
                          E_USER_WARNING);
        }
     
        $config   = array_replace_recursive($appConfig, $siteConfig);
        $config   = $this->normaliseConfig($config);
        $settings = Settings::get();
        $this->config = array_replace_recursive($settings, $config);
        return array_merge($this->config, ['routes' => $this->getRouteRule()]);
    } 
    
    /**
    * Возвращает массив маршрутов 
    *
    * @return array
    */     
    public function getRouteRule()
    { 
        if (!isset($this->config['route_rule'])) {
            return [];
        }
        
        if (is_array($this->config['route_rule'])) {
            return $this->config['route_rule'];
        }
        
        if (is_file($this->config['route_rule'])) {
            return $this->parseConfigRoutes($this->config['route_rule']);
        }
        
        trigger_error(ABC_BAD_FUNCTION_CALL_EX . 
                      ABC_UNKNOWN_ROUTES,
                      E_USER_WARNING);
    }  

    /**
    * Приводим все ключи к нижнему регистру 
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

}






