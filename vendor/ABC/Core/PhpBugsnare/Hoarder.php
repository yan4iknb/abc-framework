<?php

namespace ABC\Abc\Core\PhpBugsnare;

/** 
 * Class Hoarder
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author Nikolay Twin
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 * 
 */   
class Hoarder
{
    protected static $allReports = [];

    /**
    * It collects reports pool
    *
    * @param string $report
    *
    * @return void
    */   
    public static function add($report) 
    {       
        self::$allReports[] = $report;
    }
    
    /**
    * Return reports
    *
    * @return void
    */   
    public static function getReports() 
    { 
        return implode('<br />', self::$allReports);
    }    
}
