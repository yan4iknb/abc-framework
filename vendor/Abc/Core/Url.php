<?php 

namespace ABC\Abc\Core;

/** 
 * Класс Url
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */ 
   
class Url  
{ 
    /**
    * @var array
    */ 
    protected $config;
    
    /**
    * @var \ABC\Abc\Core\Request
    */
    protected $request;
    
    /**
    * @var \ABC\Abc\Core\Router
    */
    protected $router;
    
    /**
    * @param object $request
    */      
    public function __construct($container)
    {
        $this->config  = $container->get('config');
        $this->router  = $container->get('Router');
        $this->request = $container->get('Request');
    }
  
    /**
    * Получаем URL
    *
    * @param string $string
    * @param bool|array $mode
    *
    * @return string
    */     
    public function getUrls($string, $mode = false)
    { 
        if (isset($config['pretty']) && false === $config['pretty']) {
            $param = $this->router->hashFromUrl($string);
        } else {
            $param = $this->router->hashFromString($string);
        }
        
        return $this->createUrl($param, $mode);
    }
    
    /**
    * Добавляет параметры в URL
    *
    * @param string $string
    *
    * @return string
    */     
    public function addParamToUrl($string)
    {
        $get = $this->request->iniGET();
        $addition = $this->router->createGetFrom($string);
        $param = array_merge($get, $addition);
        return $this->createUrl($param, $abs = false);
    }
    
    /**
    * Формирование ссылок.
    * 
    * @param string $text
    * @param string $query
    * @param string $attribute
    * @param bool $abs
    *
    * @return string 
    */      
    public function linkTo($query, $text, $attribute = null, $mode = false)   
    { 
        if (substr($query, 0, 4) !== 'http') {
            $query = $this->getUrl($query, $mode);
        }
        
        return '<a href="'. $query .'" '
                          . $attribute .' >'
                          . $text 
                          .'</a>';
    } 
    
    /**   
    * Активация ссылок 
    *
    * @param string|array $param
    * @param mix $default
    *
    * @return string
    */ 
    public function activeLink($query, $default = false)
    { 
        $current = $this->router->hashFromUrl($query);
     
        if ($this->request->GET === $current) {
            return 'class="act"';        
        }        
        
        preg_match('#(.+?)/<(.*?)>#', $query, $out);
     
        if (!empty($out)) {
         
            $get = strtolower($this->request->GET('controller') .'/'. $this->request->GET('action'));
            $get = $this->getUrl($get);
            array_shift($out);
            $controller = array_shift($out);
            
            $out = explode('|', $out[0]);
            
            foreach ($out as $action) {
             
                if ($get === strtolower($this->getUrl($controller .'/'. $action))) {
                    return 'class="act"';
                }
            }
        }
     
        return null;
    } 
    
    
    /**
    * Получаем URL
    *
    * @param string $string
    * @param bool|array $mode
    *
    * @return string
    */   
    public function getUrl($string, $mode = false)
    {   
        if ($string[0] === '?') {
            mb_parse_str(trim($string, '/?'), $param); 
        } else {
            $param  = explode('/', $string);
            $param  = $this->router->generateGet($param);
        }

        return $this->createUrl($param, $mode);    
    }
    
    /**
    * Генерирует URL согласно настройкам или локальному режиму
    *
    * @param string $string
    * @param bool $mode
    *
    * @return string
    */  
    protected function createUrl($param, $mode = false)
    { 
        if (isset($mode['show_script'])) {
            unset($mode['show_script']);
        }
        
        if (is_array($mode) && !empty($this->config['url'])) {
            $config = array_merge($this->config['url'], $mode);
        } elseif (!is_array($mode) && !empty($this->config['url'])) {
            $config = $this->config['url'];
        } 

        $protocol   = !empty($config['https']) ? 'https://' : 'http://';
        $hostName   = $this->request->getHostName();
        $scriptName = null;
        
        if (!empty($config['show_script'])) {
            $query = trim($_SERVER['PHP_SELF'], '/');
            $scriptName = '/'. explode('/', $query)[0]; 
        }
       
        if (true === $mode) {
            $basePath = $protocol. $hostName . $scriptName ;
        } else {
            $basePath = (isset($config['absolute']) && true === $config['absolute']) 
                      ? $protocol . $hostName . $scriptName 
                      : $scriptName;        
        }
     
        if (isset($config['pretty']) && false === $config['pretty']) {
            return $basePath .'?'. http_build_query($param);    
        } else {
            return $basePath .'/'. implode('/', $param);
        }
    }
} 
