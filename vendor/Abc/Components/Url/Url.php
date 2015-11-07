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

    public function getUrl($string, $abs = false)
    {
        if (isset($this->config['mod_rewrite']) && false === $this->config['mod_rewrite']) {
            return $this->createQueryString($string);   
        } else {
            return $this->createRequestUri($string);
        }
    }
    
    public function createQueryString($string)
    {
        $router = Abc::getFromStorage('Router');
        $param = $router->hashFromUrl($string);
        return '/?'. http_build_query($param);   
    }
 
    
    public function createRequestUri($string)
    {
        $router = Abc::getFromStorage('Router');
        $param = $router->hashFromUrl($string);
        return '/'. implode('/', $param);   
    }    
    
} 



















