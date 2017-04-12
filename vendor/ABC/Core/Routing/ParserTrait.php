<?php

namespace ABC\Abc\Core\Routing;

use ABC\Abc\Core\Exception\AbcError;

/** 
 * Трейт RouteParser
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/
 */   
trait ParserTrait
{
    protected $defaultKeys = ['controller', 'action'];   
    protected $routeRules; 
    protected $queryString;  
    protected $current;
    protected $route;
    protected $routes;
    protected $elements;
    protected $patterns;
    
    /**
    * Добавляет параметры в URL
    *
    * @param string $queryString
    * @param bool|array $mode
    *
    * @return string
    */     
    public function addParamToUri($value, $pattern)
    {
        foreach ($this->routeRules as $rule => $location) {
            $params = explode('/', $rule);
            $last = array_pop($params);
          
            if ($last === $pattern) {
                array_push($params, $value);
                return implode('/', $params);
            }
        }
    }
    
    /**
    * Разбор правил маршрутизации
    *
    * @param string $string
    *
    * @return array
    */    
    public function parseRoutes($queryString)
    {
        $this->queryString = trim($queryString, '/') .'/';
      
        if ($this->queryString == '/') {
            return $this->defaultRoute;
        }
     
        foreach ($this->routeRules as $rule => $route) {
            $this->elements = explode('/', $this->queryString);
            $this->current = $route; 
            $this->routes = explode('/', $this->current);
            
            if ($this->resolver($rule)) {
                $get = $this->generateGet();
                $get = array_merge($this->route, $get);
                return $get;
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
     
        return preg_match('~^'. $pattern .'$~', $this->queryString);           
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
                    AbcError::logic(ABC_ERROR_ROUTES_RULE);
                    return false;
                }
            }
        }
    }
}
