<?php

namespace ABC\ABC\Resourses\Lang;

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
        define('ABC_NO_FUNCTIONAL',            ' is not implemented');
        define('ABC_NO_CLASS',                 ' class not found');
        define('ABC_NO_METHOD',                ' method offline in class ');
        define('ABC_TPL_DISABLE',              ' the template disabled');
        define('ABC_INVALID_CONFIGURE_APP',    ' Configuring the application is to be performed array');
        define('ABC_INVALID_CONFIGURE_SITE',   ' Configuring the site is to be performed array');
        define('ABC_NO_CONFIGURE',             ' Setting is not specified in the configuration file');
        define('ABC_INVALID_CONFIGURE',        'Setup key must be a string');
        define('ABC_UNKNOWN_ROUTES',           ' Unknown type of routing data');
        
        /**
        * HTTP
        */        
        define('ABC_INVALID_STREAM',           'Invalid stream provided.');
        define('ABC_INVALID_PROTOCOL',         'Invalid HTTP version.');  
        define('ABC_INVALID_TARGET',           'Invalid request target provided; cannot contain whitespace');
        define('ABC_NO_HEADER',                ' - There is no such header.');
        define('ABC_VALUE_NO_STRING',          'Header must be a string or array of strings.');
        define('ABC_INVALID_HEADER_NAME',      'Invalid header name.');        
        define('ABC_INVALID_HEADER_VALUE',     'Invalid header.');
        define('ABC_NO_RESOURCE',              ' is not a resource.');
        define('ABC_NO_REWIND',                'Could not rewind stream.');
        define('ABC_NO_POINTER',               'Could not get the position of the pointer in stream.'); 
        define('ABC_NO_WRITE',                 'Could not write to stream.');
        define('ABC_NO_READ',                  'Could not read from stream');
        define('ABC_NO_CONTENT',               'Could not get contents of stream');
        define('ABC_URI_NO_STRING',            'Uri must be a string'); 
        define('ABC_INVALID_URI',              'The invalid Uri'); 
        define('ABC_SCHEME_NO_STRING',         'Uri scheme must be a string'); 
        define('ABC_INVALID_SCHEME',           'Uri scheme must be one of: "", "https", "http"');  
        define('ABC_FRAGMENT_NO_STRING',       'Fragment must be a string');        
        define('ABC_ERROR_MOVED',              'Cannot retrieve stream after it has already been moved');
        define('ABC_EMPTY_FILE_PATH',          'No path is specified for moving the file'); 
        define('ABC_CANNOT_MOVE_FILE',         'Cannot move file');
        define('ABC_URI_IS_FRAGMENT',          'Query string must not include a URI fragment');
        define('ABC_INVALID_STATUS',          'Invalid status code. Must be an integer between 100 and 599, inclusive');
        /**
        * Debugger settings 
        */ 
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
        define('ABC_SQL_ERROR',                ' Query build error  ');
        define('ABC_TRANSACTION_EXIST',        ' There is already an active transaction');
        define('ABC_TRANSACTION_ERROR',        ' Transaction error: '); 
        define('ABC_NO_SUPPORT',               ' This type of table is not supported by the debugger'); 
        define('ABC_OTHER_OBJECT',             ' An inappropriate object is used');
        define('ABC_ERROR_BINDVALUES',         ' The numbering of parameters must begin with 1');
        define('ABC_DBCOMAND_SERIALIZE',       ' You can not serialize a query builder object');
        
        /**
        * Errors template
        */ 
        define('ABC_NO_TEMPLATE',              ' templates file  does not exist ');
        define('ABC_INVALID_BLOCK',            ' parent block does not exist or incorrect syntax ');
        define('ABC_NO_METHOD_IN_TPL',         ' templating method is not supported ');
        
        /**
        * Errors configuration
        */
        define('ABC_NO_MODEL',                 ' model is not implemented ');
        
        /**
        * Errors paginator
        */         
        define('ABC_NO_TOTAL',                 ' limit is not set ');
    }

}
















