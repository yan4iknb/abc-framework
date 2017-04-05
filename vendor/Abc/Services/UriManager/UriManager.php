<?php 

namespace ABC\Abc\Services\UriManager;

/** 
 * Класс UrlManager 
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */ 
   
class UriManager  
{ 

    protected $config;
    protected $parser;
    
    /**
    * @param object $abc
    */      
    public function __construct($abc)
    {
        $this->uriConfig  = $abc->getConfig('uri_manager');
        $this->params = $abc->sharedService('Params');
        $this->router = $abc->getFromStorage('Router');
    }
    
    /**
    * Генерирует URL согласно настройкам или локальному режиму
    *
    * @param string $queryString
    * @param bool|array $mode
    *
    * @return string
    */  
    public function createUri($queryString, $mode = false)
    { 
        if (substr($queryString, 0, 4) === 'http') {
            return $queryString;
        }
     
        $queryString  = trim($queryString, '/'); 
     
        if (is_array($mode) && !empty($this->uriConfig)) {
            $config = array_merge($this->uriConfig, $mode);
        } elseif (!is_array($mode) && !empty($this->uriConfig)) {
            $config = $this->uriConfig;
        } 
     
        $protocol   = !empty($config['https']) ? 'https://' : 'http://';
        $hostName   = $this->params->getHostName();
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
                $param = $this->router->parseRoutes($queryString);
                return $basePath .'?'. http_build_query($param); 
            }
            
        } else {
         
            if ($queryString[0] !== '?') {
                return $basePath .'/'. $queryString;
            } else {
                mb_parse_str($queryString, $param); 
                $param = $this->router->createHashFromParam($param);
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
    public function addParamToUri($queryString, $mode = false)
    {
        $get = $this->params->get();
        $addition = $this->router->createGetFromPath($queryString);
        $param = array_merge($get, $addition);
        $queryString = '?'. http_build_query($param); 
        return $this->createUri($queryString, $mode);
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
        
        if (!empty($param['returnUri']) && !empty($param['css'])) {
            $attribute .= $this->activeLink($param['returnUri'], $param['css']); 
        } elseif (!empty($param['returnUri'])) {
            $attribute .= $this->activeLink($param['returnUri']);
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
    * @param string $activeCss
    *
    * @return string
    */ 
    public function activeLink($returnUrl, $activeCss = 'class="active"')
    {
        $current = strtolower($this->params->getController() .'/'. $this->params->getAction());
     
        if ($current === $returnUrl) {
            return $activeCss;        
        }        
       
        preg_match('#(.+?)/<(.*?)>#', $returnUrl, $out);
      
        if (!empty($out)) {
            $check = explode('|', $out[2]);
            
            foreach ($check as $action) {
             
                if ($current === $out[1] .'/'. $action) {
                    return $activeCss;
                }
            }
        }
     
        return null;
    } 
} 
