<?php

namespace ABC;

    error_reporting(E_ALL);
    require __DIR__ .'/../vendor/Abc/Abc.php';  
    $config = require __DIR__ .'/../app/resourses/config.php';
   
    Abc::createApp($config);

    
    
    
    
    
