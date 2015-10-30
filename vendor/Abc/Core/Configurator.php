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
            throw new \InvalidArgumentException('Configuring the application is to be performed array');
        }
        
        if (!is_array($siteConfig)) {
            throw new \InvalidArgumentException('Configuring the site is to be performed array');
        }
     
        $this->config = array_merge($appConfig, $siteConfig);
        return $this->config;
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






