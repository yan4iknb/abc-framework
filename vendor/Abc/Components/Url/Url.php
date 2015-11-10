<?php 

namespace ABC\Abc\Components\Url;

use ABC\Abc;

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
    public $config;
    public $router;
    
    public function getUrl($string, $abs = false)
    {
        $param = $this->router->hashFromUrl($string);    
        return $this->createUrl($param, $abs = false);

    }
    
    public function addParamToUrl($string)
    {
        $get = Abc::GET();
        $addition = $this->router->createGetFrom($string);
        $param = array_merge($get, $addition);
        return $this->createUrl($param, $abs = false);
    }
 
    protected function createUrl($param, $abs = false)
    {
        if (isset($this->config['mod_rewrite']) && false === $this->config['mod_rewrite']) {
            return '/?'. http_build_query($param);    
        } else {
            $param = $this->router->hashFromQueryString($param);
            return '/'. implode('/', $param);
        }
    }
    
    public function createGetFrom($string)
    {
        return $this->router->createGetFrom($string);
    }
    
    public function getGet($string)
    {
        return $this->router->hashFromUrl($string);
    }
    
} 



















