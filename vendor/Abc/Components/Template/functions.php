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
        return Abc::getService('Url')->getUrl($query, $mode);
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
        if (substr($query, 0, 4) !== 'http') {
            $query = href($query, $mode);
        }
        
        return '<a href="'. $query .'" '
                          . $attribute .' >'
                          . $text 
                          .'</a>';
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
        $url = Abc::getService('Url');
        $current = $url->getGet($query);
     
        if (Abc::GET() === $current) {
            return 'class="act"';        
        }        
        
        preg_match('#(.+?)/<(.*?)>#', $query, $out);
     
        if (!empty($out)) {
         
            $get = strtolower(Abc::GET('controller') .'/'. Abc::GET('action'));
            $get = $url->getUrl($get);
            array_shift($out);
            $controller = array_shift($out);
            
            $out = explode('|', $out[0]);
            
            foreach ($out as $action) {
             
                if ($get === strtolower($url->getUrl($controller .'/'. $action))) {
                    return 'class="act"';
                }
            }
        }
     
        return null;
    } 
    
  