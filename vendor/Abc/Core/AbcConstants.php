<?php

namespace ABC\Abc\Core;

/** 
 * Класс Constants
 * Предустановленные константы фреймворка
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/
 */  

class AbcConstants
{
    /**
    * Устанавливает предопределенные константы
    */     
    public static function set() 
    { 
        /**
        * Константы кодов иерархии исключений SPL
        */  
        define('ABC_EXCEPTION', '[0000]'); 
        define('ABC_BAD_FUNCTION_CALL_EX', '[001]');
        define('ABC_BAD_METHOD_CALL_EX', '[002]');    
        define('ABC_DOMAIN_EX', '[0003]');
        define('ABC_INVALID_ARGUMENT_EX', '[004]');
        define('ABC_LENGTH_EX', '[005]');    
        define('ABC_LOGIC_EX', '[006]');
        define('ABC_OUT_OF_BOUNDS_EX', '[007]');    
        define('ABC_OUT_OF_RANGE_EX', '[008]');         
        define('ABC_OVERFLOW_EX', '[009]');    
        define('ABC_RANGE_EX', '[010]'); 
        define('ABC_RUNTIME_EX', '[011]');    
        define('ABC_UNDERFLOW_EX', '[012]');
        define('ABC_UNEXPECTED_VALUE_EX', '[013]');
    }

}  




















































