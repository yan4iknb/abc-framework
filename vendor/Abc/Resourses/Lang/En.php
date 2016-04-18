<?php

namespace ABC\Abc\Resourses\Lang;

/** 
 * Класс En
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */   
class En
{
    /**
    * Устанавливает языковые константы
    */     
    public static function set() 
    {
        /**
        * General constants
        */ 
        define('ABC_NO_CLASS',     ' class not found');
        define('ABC_NO_METHOD',    ' method offline in class ');
        define('ABC_TPL_DISABLE',  ' the template disabled');
        
        /**
        * General settings
        */ 
        define('ABC_INVALID_CONFIGURE_APP',    ' Configuring the application is to be performed array');
        define('ABC_INVALID_CONFIGURE_SITE',   ' Configuring the site is to be performed array');
        define('ABC_UNKNOWN_ROUTES',           ' Unknown type of routing data');
        
        /**
        * Debugger settings 
        */ 
        define('ABC_INVALID_DEBUG_SETTING',    ' Incorrect configuration debugging');
        define('ABC_TRACING_VARIABLE',         ' Tracing Variable ');
        define('ABC_TRACING_OBJECT',           ' Tracing Object ');
        define('ABC_TRACING_CONTAINER',        ' Tracing Container ');        
        define('ABC_TRACING_CLASS',            ' Tracing Class ');
        
        /**
        * Errors use container dependencies
        */ 
        define('ABC_INVALID_SERVICE_NAME',     ' Service name should be a string'); 
        define('ABC_NO_SERVICE',               ' service is not defined'); 
        define('ABC_ALREADY_SERVICE',          ' service is already installed');       
        define('ABC_NOT_FOUND_SERVICE',        ' Service not found'); 
        define('ABC_INVALID_CALLABLE',         ' Argument must be a function of anonymity is conferred');
        define('ABC_SYNTHETIC_SERVICE',        ' service created synthetically. Impossible to implement services according to the synthetic');
        define('ABC_INVALID_PROPERTY',         ' Property should be a array');
        define('ABC_NOT_REGISTERED_SERVICE',   ' service is not registered in a container');
        
        /**
        * Errors using components database
        */ 
        define('ABC_WRONG_CONNECTION',         ' wrong data connection in the configuration file');        
        define('ABC_NO_SQL_DEBUGGER',          ' SQL debugger is inactive. Set to true debug configuration.');    
        define('ABC_INVALID_MYSQLI_TYPE',      ' Number of elements in type definition string doesn\'t match number of bind variables ');
        define('ABC_NO_MYSQLI_TYPE',           ' Unknown type of the parameter ');
     
        /**
        * Errors template
        */ 
        define('ABC_NO_TEMPLATE',              ' templates file  does not exist ');
        define('ABC_INVALID_BLOCK',            ' parent block does not exist or incorrect syntax ');
        define('ABC_NO_METHOD_IN_TPL',         ' templating method is not supported ');
        
        define('ABC_NO_MODEL',                 ' model is not implemented ');
    }

}
















