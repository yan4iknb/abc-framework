<?php 

namespace ABC\Abc\Components\UrlManager;

/** 
 * Класс Url
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */ 
   
class UrlManager  
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
    public function __construct($abc)
    {
        $this->config  = $abc->getConfig();
        $this->router  = $abc->getFromStorage('Router');
        $this->request = $abc->getFromStorage('Request');
    }
    
    /**
    * Генерирует URL согласно настройкам или локальному режиму
    *
    * @param string $queryString
    * @param bool|array $mode
    *
    * @return string
    */  
    public function createUrl($queryString, $mode = false)
    { 
        if (substr($queryString, 0, 4) === 'http') {
            return $queryString;
        }
     
        $queryString  = trim($queryString, '/'); 
        
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
            $basePath = $protocol. $hostName . $scriptName;
        } elseif (false === $mode) {
            $basePath = $scriptName;
        } else {
            $basePath = (isset($config['absolute']) && true === $config['absolute']) 
                      ? $protocol . $hostName . $scriptName 
                      : $scriptName;        
        }
     
        if (isset($config['pretty']) && false === $config['pretty']) {
         
            if ($queryString[0] === '?') {
                return $basePath .'?'. ltrim($queryString, '?');            
            } else {
                $param = explode('/', $queryString);
                $param = $this->router->defaultGet($param);
                return $basePath .'?'. http_build_query($param); 
            }
            
        } else {
         
            if ($queryString[0] !== '?') {
                return $basePath .'/'. $queryString;
            } else {
                mb_parse_str($queryString, $param); 
                $param = $this->router->hashFromParam($param);
                $queryString = implode('/', $param);
                return $basePath .'/'. $queryString;
            }
        }
    }    
    
    /**
    * Добавляет параметры в URL
    *
    * @param string $queryString
    * @param bool|array $mode
    *
    * @return string
    */     
    public function addParamToUrl($queryString, $mode = false)
    {
        $get = $this->request->iniGET();
        $addition = $this->router->createGetFrom($queryString);
        $param = array_merge($get, $addition);
        return $this->createUrl($param, $mode);
    }
    
    /**
    * Формирование ссылок.
    * 
    * @param string $queryString
    * @param string $text
    * @param array  $param
    *
    * @return string 
    */      
    public function createLink($queryString, $text, $param = [])   
    { 
        $attribute = !empty($param['attribute']) ? $param['attribute'] : null;
        
        if (!empty($param['returnUrl']) && !empty($param['css'])) {
            $attribute .= $this->activeLink($param['returnUrl'], $param['css']); 
        } elseif (!empty($param['returnUrl'])) {
            $attribute .= $this->activeLink($param['returnUrl']);
        }        
            
        $mode = !empty($param['mode']) ? $param['mode'] : false; 
        $queryString = $this->createUrl($queryString, $mode);  
        
        return '<a href="'. $queryString .'" '
                          . $attribute .' >'
                          . $text 
                          .'</a>';
    } 
    
    /**   
    * Активация ссылок 
    *
    * @param string $returnUrl
    * @param string $css
    *
    * @return string
    */ 
    public function activeLink($returnUrl, $css = 'class="active"')
    {
        $current = $this->router->hashFromUrl($returnUrl);
     
        if (iniGET() === $current) {
            return $css;        
        }        
        
        preg_match('#(.+?)/<(.*?)>#', $returnUrl, $out);
     
        if (!empty($out)) {
         
            $get = strtolower(iniGET('controller') .'/'. iniGET('action'));
            $get = $this->createUrl($get);
            array_shift($out);
            $controller = array_shift($out);
            
            $out = explode('|', $out[0]);
            
            foreach ($out as $action) {
             
                if ($get === strtolower($this->createUrl($controller .'/'. $action))) {
                    return $css;
                }
            }
        }
     
        return null;
    } 
} 
