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
    protected $config;

    protected $defaultKeys = ['controller', 'action'];    
    protected $defaultRoute;
    protected $routeRules; 
    protected $string;  
    protected $current;
    protected $routes;
    protected $elements;
    protected $patterns;
    
    /**
    * @param object $container
    */ 
    public function __construct($container)
    {
        $this->container = $container;
        $this->config    = $container->get('config'); 
        $this->defaultRoute = arrayStrtolower($this->config['defaultRoute']);
        $this->routeRules = $this->config['route_rules'];
    }     
    
    /**
    * Разбор правил маршрутизации
    *
    * @param string $string
    *
    * @return array
    */    
    public function parseRoutes($string)
    {
        $this->string = trim($string, '/') .'/';
      
        if ($this->string == '/') {
            return $this->defaultRoute;
        }
     
        foreach ($this->routeRules as $rule => $route) {
            $this->elements = explode('/', $this->string);
            $this->current = $route; 
            $this->routes = explode('/', $this->current);
            
            if ($this->resolver($rule)) {
                $get = $this->generateGet();
                return array_merge($this->route, $get);
            }    
        }
     
        return [];
    } 
    
    /**
    * Распознование подходящего правила
    *
    * @param string $patterns
    *
    * @return array
    */    
    protected function resolver($rule)
    {
        $pattern = '';
        $sections = $this->preapareSections($rule);
        
        foreach ($sections as $num => $section) {
            if (is_array($section)) {
                $pattern .= '('. $section['value'] .'?)/'; 
            } else {
                 $pattern .= $section .'/';
            }
        }
     
        return preg_match('~^'. $pattern .'$~', $this->string);           
    }
    
    /**
    * Подготовка шаблонов для RegExp
    *
    * @param string $rule
    *
    * @return array
    */    
    protected function preapareSections($rule)
    { 
        $rule = explode('/', $rule);
        $this->patterns = [];
        
        foreach ($rule as $num => $section) {
            if (preg_match_all('~<([\w._-]+)?>~', $section, $out)) {
                $this->patterns[] = ['name' => $out[1][0], 'value' => '[^/]+'];
            } elseif (preg_match_all('~<([\w._-]+):?([^>]+)?>~', $section, $out)) {
                $this->patterns[] = ['name' => $out[1][0], 'value' => $out[2][0]]; 
            } else {
                $this->patterns[] = $section;
            }
        }
        
        return $this->patterns;
    } 
    
    /**
    * Генерация массива GET параметров
    *
    * @return array
    */    
    protected function generateGet()
    {
        $get = []; 
     
        foreach ($this->patterns as $num => $pattern) {
            if (is_array($pattern)) {
                if (preg_match('~'. $pattern['value'] .'~', $this->elements[$num])) {
                    $get[$pattern['name']] = $this->elements[$num];
                }
            } else {
                $elements = $this->elements;
                $path = array_shift($elements);
                
                if ($path !== $pattern && preg_match('~'. $pattern .'~', $this->elements[$num])) {
                    $get[$pattern] = $this->elements[$num]; 
                }   
            }
        }
        
        $this->setRoute();
        return $get;    
    }
    
    /**
    * Установка маршрутов
    *
    * @return void
    */ 
    protected function setRoute($default = false)
    { 
        if($default){
            $this->route = $this->defaultRoute;
        } else {
         
            foreach ($this->routes as $num => $rout) {
                if (!empty($this->defaultKeys[$num])) {
                    $this->route[$this->defaultKeys[$num]] = $rout;
                } else {
                    Response::logicException(ABC_ERROR_ROUTES_RULE);
                }
            }
        }
    }
}