<?php

namespace ABC\Abc\Core;

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
    protected $defaultRoutes = [
    
              ];
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
            trigger_error(ABC_INVALID_ARGUMENT_EX
                         .' Configuring the application is to be performed array',
                         E_USER_WARNING);
        }
        
        if (!is_array($siteConfig)) {
            trigger_error(ABC_INVALID_ARGUMENT_EX
                         .' Configuring the site is to be performed array',
                         E_USER_WARNING);
        }
     
        $this->config = array_merge($appConfig, $siteConfig);
        return $this->normaliseConfig($this->config);
    } 
    
    /**
    * Возвращает массив маршрутов 
    *
    * @return array
    */     
    public function getRoutes()
    { 
        return prepareRoutes();
    }    
    
    /**
    * Приводит все элементы к нижнему регистру 
    *
    * @return array
    */     
    public function normaliseConfig($config)
    { 
        return array_change_key_case($config);
    }    
    

    
    /**
    * Разбирает настройки маршрутов 
    *
    * @return array
    */     
    protected function prepareRoutes()
    { 
        if (!isset($this->config['routes'])) {
            return $this->defaultRoute();
        }
        
        if (is_array($this->config['routes'])) {
            return $this->config['routes'];
        }
        
        if (is_file($this->config['routes'])) {
            return $this->parseConfigRoutes($this->config['routes']);
        }
        
        throw new \DomainException('Unknown type of routing data.');
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
        $configRoute = file_get_contents($file);
        // To be continued
        return [];
    } 
    
    /**
    * Устанавливает дефолтные правили маршрутизации 
    *
    * @return array
    */     
    protected function defaultRoute()
    { 
        return $this->defaultRoutes;
    }
}






