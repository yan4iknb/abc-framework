<?php

namespace ABC\Abc\Core\PhpBugsnare;

use ABC\Abc\Core\PhpBugsnare\Levels\Fatal;
use ABC\Abc\Core\PhpBugsnare\Levels\NoFatal;
use ABC\Abc\Core\PhpBugsnare\Hoarder;


class Bugsnare
{
    public function __construct($config)
    {
        ob_start();
        error_reporting(-1);   
     
        $fatal = new Fatal($config);
        register_shutdown_function([$fatal, 'errorHandler']);
        new NoFatal($config); 
    }
    
    public static function getReport()
    {
        return Hoarder::getReports();
    }    
}