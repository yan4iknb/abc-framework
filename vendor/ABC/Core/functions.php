<?php

use ABC\ABC;
use ABC\ABC\Core\PhpBugsnare\Debugger;

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
    *i
    * @param array $key
    * @param array $default
    * 
    * @return string
    */
    function GET($key = null, $default = null)
    {
        $abc = abcForFunctions();
        return $abc->sharedService(ABC::PARAMS)->get($key, $default);
    }
    
    /**
    * Инициализация POST
    *
    * @param array $key
    * @param array $default
    * 
    * @return string
    */
    function POST($key = null, $default = null)
    {
        $abc = abcForFunctions();
        return $abc->sharedService(ABC::PARAMS)->post($key, $default);
    }
    
    /**
    * Инициализация COOKIE
    *
    * @param array $key
    * @param array $default
    * 
    * @return string
    */
    function COOKIE($key = null, $default = null)
    {
        $abc = abcForFunctions();
        return $abc->sharedService(ABC::PARAMS)->cookie($key, $default);
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
    * @param string $queryString
    * @param bool|array $mode
    *
    * @return string 
    */      
    function createUri($queryString, $mode = false)   
    {  
        $abc = abcForFunctions();
        return $abc->sharedService(ABC::URI_MANAGER)->createUri($queryString, $mode);
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
        return $abc->sharedService(ABC::URI_MANAGER)->createLink($queryString, $text, $param);
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
        return $abc->sharedService(ABC::URI_MANAGER)->activeLink($returnUrl, $css);
    }     

    /**
    * Трассировка скриптов
    *
    * @return void
    */ 
    function dbg($var = 'stop')
    { 
        $abc = abcForFunctions();
        $config = $abc->getConfig();
        new Debugger($var, $config['debug']);
    }
  