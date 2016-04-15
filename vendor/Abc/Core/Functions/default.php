<?php

use ABC\abc;
use ABC\Abc\Core\Debugger\Dbg;
    /**
    * Processing variables for output stream
    *
    * @param array $data
    * 
    * @return mix
    */
    function htmlChars($data)
    {
        if (is_array($data)) {
            $data = array_map([$this, 'htmlChars'], $data);
        } else {
            $data = htmlspecialchars($data);
        }
        
        return $data;
    }

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

    /**
    * Трассировка скриптов
    *
    * @return void
    */ 
    function dbg($var = 'stop')
    {   
        new Dbg($var);
    }