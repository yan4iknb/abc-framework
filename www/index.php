<?php

namespace ABC;

    require_once __DIR__ .'/../vendor/abc/abc.php'; 
    $config = require_once __DIR__ .'/../versions/app/resourses/config.php';
   
    Abc::createNew($config);
    //echo Abc::current()->install();
    echo Abc::getVersion('debuggers');
    
    
    
    
    