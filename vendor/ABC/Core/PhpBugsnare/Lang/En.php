<?php

namespace ABC\ABC\Core\PhpBugsnare\Lang;

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
    protected static function errorReportings() 
    {
        return [];
    }

    public static function translate($message) 
    {
        return $message;
    }

    protected static function errorReportingsSql() 
    {
        return [];
    }

    public static function translateSql($message) 
    {
        return $message;
    }    
    
}
