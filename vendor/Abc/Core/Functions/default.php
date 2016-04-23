<?php

use ABC\Abc;
use ABC\Abc\Core\Debugger\Dbg;

    function abcForFunctions($abc = null)
    {
        static $ABC;
        
        if (null === $ABC) {
            $ABC = $abc;        
        } 
        
        return $ABC; 
    }
    
    /**
    * Обработка переменных для вывода в поток
    *
    * @param array $data
    * 
    * @return mix
    */
    function iniGET($key = null, $default = null)
    {
        $abc = abcForFunctions();
        return $abc->getFromStorage('Request')->iniGET($key, $default);
    }
    
    /**
    * Обработка переменных для вывода в поток
    *
    * @param array $data
    * 
    * @return mix
    */
    function iniPOST($key = null, $default = null)
    {
        $abc = abcForFunctions();
        return $abc->getFromStorage('Request')->iniPOST($key, $default);
    }

    /**
    * Обработка переменных для вывода в поток
    *
    * @param array $data
    * 
    * @return mix
    */
    function htmlChars($data)
    {
        if (is_array($data)) {
            $data = array_map('htmlChars', $data);
        } else {
            $data = htmlspecialchars($data);
        }
        
        return $data;
    }
    
    /**
    * Преобразует элементы массива в нижний регистр
    *
    * @param array $data
    * 
    * @return mix
    */
    function arrayStrtolower($data)
    {
        if (is_array($data)) {
            $data = array_map('arrayStrtolower', $data);
        } else {
            $data = mb_strtolower($data);
        }
        
        return $data;
    }
    
    /**
    * Преобразует элементы массива в верхний регистр
    *
    * @param array $data
    * 
    * @return mix
    */
    function arrayStrtoupper($data)
    {
        if (is_array($data)) {
            $data = array_map('arrayStrtoupper', $data);
        } else {
            $data = mb_strtoupper($data);
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
        $abc = abcForFunctions();
        return $abc->getService('Url')->getUrl($query, $mode);
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
        $abc = abcForFunctions();
        return $abc->getService('Url')->linkTo($query, $text, $attribute, $mode);
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
        $abc = abcForFunctions();
        return $abc->getService('Url')->activeLink($query, $default);
    }     

    /**
    * Трассировка скриптов
    *
    * @return void
    */ 
    function dbg($var = 'stop')
    { 
        $abc = abcForFunctions();
        new Dbg($var, $abc);
    }
  