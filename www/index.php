<?php

namespace ABC;

    error_reporting(E_ALL);
    
    require __DIR__ .'/../vendor/abc/Abc.php';
    //require __DIR__ .'/../vendor/abc/core/Autoloader.php'; 
    
    $config = require __DIR__ .'/../app/resourses/config.php';
   
    Abc::createApp($config);
    //(new \ABC\abc\core\AbcProcessor($config))->route();

    
    
    
    
    