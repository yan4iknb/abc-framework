<?php

use ABC\Abc;
use ABC\Abc\Components\Debugger\Trace\Dbg;

    function abcForFunctions($abc = null)
    {
        static $ABC;
        
        if (null === $ABC) {
            $ABC = $abc;        
        } 
        
        return $ABC; 
    }
    
    /**
    * Инициализация GET
    *
    * @param array $key
    * @param array $default
    * 
    * @return string
    */
    function iniGET($key = null, $default = null)
    {
        $abc = abcForFunctions();
        return $abc->getFromStorage('Request')->iniGET($key, $default);
    }
    
    /**
    * Инициализация POST
    *
    * @param array $key
    * @param array $default
    * 
    * @return string
    */
    function iniPOST($key = null, $default = null)
    {
        $abc = abcForFunctions();
        return $abc->getFromStorage('Request')->iniPOST($key, $default);
    }
    
    /**
    * Инициализация COOKIE
    *
    * @param array $key
    * @param array $default
    * 
    * @return string
    */
    function iniCOOKIE($key = null, $default = null)
    {
        $abc = abcForFunctions();
        return $abc->getFromStorage('Request')->iniCOOKIE($key, $default);
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
            return array_map('htmlChars', $data);
        } else {
            return htmlspecialchars($data);
        }
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
            return array_map('arrayStrtolower', $data);
        } else {
            return mb_strtolower($data);
        }
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
            return array_map('arrayStrtoupper', $data);
        } else {
            return mb_strtoupper($data);
        }
    }

    /**
    * Формирование URL.
    * 
    * @param string $queryString
    * @param bool|array $mode
    *
    * @return string 
    */      
    function createUrl($queryString, $mode = false)   
    {  
        $abc = abcForFunctions();
        return $abc->sharedService('UrlManager')->createUrl($queryString, $mode);
    }
    
    /**
    * Формирование ссылок.
    * 
    * @param string $queryString
    * @param string $text
    * @param array $param
    *
    * @return string 
    */      
    function createLink($queryString, $text, $param = [])   
    { 
        $abc = abcForFunctions();
        return $abc->sharedService('UrlManager')->createLink($queryString, $text, $param);
    } 
    
    /**   
    * Активация ссылок 
    *
    * @param string $returnUrl
    * @param string $css
    *
    * @return string
    */ 
    function activeLink($returnUrl, $css = 'class="active"')
    { 
        $abc = abcForFunctions();
        return $abc->sharedService('UrlManager')->activeLink($returnUrl, $css);
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
  