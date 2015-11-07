<?php 

use ABC\Abc;

/** 
 * Шаблонизатор
 * Набор вспомогательных функций 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */ 
// - - - - - - - - - - - - - - - - - - - - - - -

    /**
    * Формирование URL.
    * 
    * @param string $arg
    *
    * @return string 
    */      
    function href($query, $abs = false)   
    {  
        return Abc::getService('Url')->getUrl($query, $abs = false);
    }
    
    /**
    * Формирование ссылок.
    * 
    * @param string $text
    * @param string $query
    * @param string|array $param
    * @param string $return
    * @param mix $default
    * @param bool $abs
    *
    * @return string 
    */      
    function linkTo(...$args)   
    { 
        $default = ['param' => null, 'return' => null, 'default' => false, 'abs' => false];
        $args = array_merge($default, $args);
        extract($args);
        return '<a href="'. href($query, $abs) .'" '
                          . activeLink($param, $return, $default) .'>'
                          . htmlspecialchars($text) 
                          .'</a>';
    } 
    
    /**   
    * Активация ссылок 
    *
    * @param string $return
    * @param string|array $param
    * @param mix $default
    *
    * @return string
    */ 
    function activeLink($param, $return, $default = false)
    { 
        $value = Abc::GET($param);
     
        if($default && $value === '')
            return 'class="active"';
     
        if(is_array($return) && in_array($value, $return))
            return 'class="active"';
     
        return ($value === $return) ? 'class="active"' : NULL;
    } 
    
  