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
        return Abc::getService('Url')->getUrl($query, $abs);
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
    function linkTo(...$args)   
    { 
        $default = ['attribute' => null, 'abs' => false];
        $args = array_merge($default, $args);
        extract($args);
        return '<a href="'. href($query, $abs) .'" '
                          . $attribute .' >'
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
    function activeLink($query, $default = false)
    { 
        $get = Abc::GET();
        $current = Abc::getService('Url')->getGet($query);

        if ($get === $current) {
            return 'class="act"';        
        }
        
        if (null === $get['controller'] && $default) {
            return 'class="act"'; 
        }
     
        
        return null;
    } 
    
  