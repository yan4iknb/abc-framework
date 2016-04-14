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
    * Получаем URL согласно роутам
    *
    * @param string $string
    * @param bool|array $mode
    *
    * @return string
    */     
    public function getUrl($string, $mode = false)
    {
        $param = $this->router->hashFromUrl($string); 
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
        $get = Abc::GET();
        $addition = $this->router->createGetFrom($string);
        $param = array_merge($get, $addition);
        return $this->createUrl($param, $abs = false);
    }
    
    /**
    * Преобразует строку URL в массив согласно роутам
    *
    * @param string $string
    *
    * @return array
    */     
    public function createGetFrom($string)
    {
        return $this->router->createGetFrom($string);
    }
    
    /**
    * Получает массив GET параметров
    *
    * @param string $string
    *
    * @return array
    */     
    public function getGet($string)
    {
        return $this->router->hashFromUrl($string);
    }
    
    /**
    * Формирование URL для ссылок.
    * 
    * @param string $arg
    * @param bool|array $mode
    *
    * @return string 
    */      
    public function href($query, $mode = false)   
    {  
        return $this->getUrl($query, $mode);
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
            $query = $this->href($query, $mode);
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
        $current = $this->getGet($query);
     
        if ($this->request->GET() === $current) {
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
    * Генерирует URL согласно роутам или локальному режиму
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
            $basePath = !empty($config['absolute']) ? $protocol . $hostName . $scriptName : $scriptName;        
        }
     
        if (isset($config['pretty']) && false === $config['pretty']) {
            return $basePath .'?'. http_build_query($param);    
        } else {
            $param = $this->router->hashFromParam($param);
            return $basePath .'/'. implode('/', $param);
        }
    }
} 



















