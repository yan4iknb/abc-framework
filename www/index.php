<?php

namespace ABC;

    error_reporting(E_ALL);
    require __DIR__ .'/../vendor/Abc/Abc.php';  
    $config = require __DIR__ .'/../App/Resourses/Config.php';
   
    Abc::createApp($config);

    
    
    
    
    
