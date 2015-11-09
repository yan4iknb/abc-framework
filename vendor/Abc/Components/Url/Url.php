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
        $router = Abc::getFromStorage('Router');
        $param = $router->hashFromUrl($string);    
     
        if (isset($this->config['mod_rewrite']) && false === $this->config['mod_rewrite']) {
            return '/?'. http_build_query($param);    
        } else {
            $param = $router->hashFromQueryString($param);
            return '/'. implode('/', $param);
        }
    }
    
    public function getGet($string, $abs = false)
    {
        $router = Abc::getFromStorage('Router');
        $param = $router->hashFromUrl($string);
        return $param;
    }
} 



















