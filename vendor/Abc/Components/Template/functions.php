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
    * @param bool|array $mode
    *
    * @return string 
    */      
    function href($query, $mode = false)   
    {  
        return Abc::getFromStorage('Url')->getUrl($query, $mode);
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
    function linkTo($query, $text, $attribute = null, $mode = false)   
    { 
        return Abc::getFromStorage('Url')->linkTo($query, $text, $attribute, $mode);
    } 
    
    /**   
    * Активация ссылок 
    *
    * @param string|array $param
    * @param mix $default
    *
    * @return string
    */ 
    function activeLink($query, $default = false)
    { 
        return Abc::getService('Url')->activeLink($query, $default);
    } 
    
  