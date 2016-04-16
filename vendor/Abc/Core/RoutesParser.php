<?php

namespace ABC\Abc\Core;

/** 
 * Класс RouteParser
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */   
class RoutesParser
{
    /**
    * @var ABC\Abc\Core\Container
    */ 
    protected $container;
    
    /**
    * @var array
    */ 
    protected $config;
    
    /**
    * @var array
    */ 
    protected $defaultRoute;
    
    /**
    * @param object $container
    */ 
    public function __construct($container)
    {
        $this->container = $container;
        $this->config    = $container->get('config'); 
        $this->defaultRoute = arrayStrtolower($this->config['defaultRoute']);
    }     
    
    /**
    * 
    *
    * @param $uriHash
    *
    * @return array
    */    
    public function parseRoute($string)
    {
        $rules = $this->config['routes'];
        $hash  = explode('/', $string);
        $other = $default = [];
        
        foreach ($rules as $rule => $value) {
         
            if ($pattern = $this->routesResolver($rule, $string)) {
                $default = $this->prepareRoute($value);
                $other   = $this->prepareGet($pattern, $string);
            }
        }   
        
        return array_merge($this->defaultRoute, $default, $other);   
    } 
    
    /**
    * 
    *
    * @param $uriHash
    *
    * @return array
    */    
    protected function routesResolver($pattern, $string)
    {
        $string  = trim($string, '/');
        $pattern = preg_replace('~(.*?)/~i', '($1+?)/', $pattern);
        $pattern = preg_replace('~<([^:]+?)>~i', '$1', $pattern);
        $pattern = preg_replace('~<(.*?):(.+?)>~i', '($2+?)', $pattern);
        
        if (preg_match('~^'. $pattern .'$~i', $string)) {
            return $pattern;
        }
        
        return false; 
    }
    
    /**
    * 
    *
    * @param $uriHash
    *
    * @return array
    */    
    protected function prepareRoute($rule)
    {
        $routeKeys = ['controller', 'action'];
        $routes = explode('/', $rule);
        $route  = []; 
        
        foreach ($routes as $k => $default) {
            if (!empty($default)) {
                $route[$routeKeys[$k]] = $default;
            }
        } 
        
        return $route;
    }
    
    /**
    * 
    *
    * @param $rule
    *
    * @return array
    */    
    protected function prepareGet($pattern, $string)
    {
        preg_match_all('~^'. $pattern .'$~i', $string, $out);
        // продолжение следует
        return [];
    }
}
