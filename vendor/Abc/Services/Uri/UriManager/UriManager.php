<?php 

namespace ABC\Abc\Services\Uri\UriManager;

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
        $this->config  = $abc->getConfig();
        $this->request = $abc->getFromStorage('Request');
        $this->parser  = $abc->sharedService('Router');
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
     
        if (is_array($mode) && !empty($this->config['url_manager'])) {
            $config = array_merge($this->config['url_manager'], $mode);
        } elseif (!is_array($mode) && !empty($this->config['url_manager'])) {
            $config = $this->config['url_manager'];
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
                $param = $this->parser->parseRoutes($queryString);
                return $basePath .'?'. http_build_query($param); 
            }
            
        } else {
         
            if ($queryString[0] !== '?') {
                return $basePath .'/'. $queryString;
            } else {
                mb_parse_str($queryString, $param); 
                $param = $this->parser->createHashFromParam($param);
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
        $get = $this->request->get();
        $addition = $this->parser->createGetFromPath($queryString);
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
    * @param string $activeCss
    *
    * @return string
    */ 
    public function activeLink($returnUrl, $activeCss = 'class="active"')
    {
        $current = strtolower($this->request->getController() .'/'. $this->request->getAction());
     
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
