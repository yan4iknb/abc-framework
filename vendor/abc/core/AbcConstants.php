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
    * Конструктор
    * 
    */     
    public static function set() 
    { 
        /**
        * Константы кодов иерархии исключений SPL
        */  
        defined('ABC_EXCEPTION') or define('ABC_EXCEPTION', '[0000]'); 
        defined('ABC_BAD_FUNCTION_CALL_EX') or define('ABC_BAD_FUNCTION_CALL_EX', '[001]');
        defined('ABC_BAD_METHOD_CALL_EX') or define('ABC_BAD_METHOD_CALL_EX', '[002]');    
        defined('ABC_DOMAIN_EX') or define('ABC_DOMAIN_EX', '[0003] ');
        defined('ABC_INVALID_ARGUMENT_EX') or define('ABC_INVALID_ARGUMENT_EX', '[004]');
        defined('ABC_LENGTH_EX') or define('ABC_LENGTH_EX', '[005] ');    
        defined('ABC_LOGIC_EX') or define('ABC_LOGIC_EX', '[006] ');
        defined('ABC_OUT_OF_BOUNDS_EX') or define('ABC_OUT_OF_BOUNDS_EX', '[007]');    
        defined('ABC_OUT_OF_RANGE_EX') or define('ABC_OUT_OF_RANGE_EX', '[008]');         
        defined('ABC_OVERFLOW_EX') or define('ABC_OVERFLOW_EX', '[009]');    
        defined('ABC_RANGE_EX') or define('ABC_RANGE_EX', '[010]'); 
        defined('ABC_RUNTIME_EX') or define('ABC_RUNTIME_EX', '[011]');    
        defined('ABC_UNDERFLOW_EX') or define('ABC_UNDERFLOW_EX', '[012] ');
        defined('ABC_UNEXPECTED_VALUE_EX') or define('ABC_UNEXPECTED_VALUE_EX', '[013]');    

    }

}  




















































