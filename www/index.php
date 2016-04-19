<?php

namespace ABC;

    require __DIR__ .'/../vendor/Abc/Abc.php';  
    $config = require __DIR__ .'/../App/Resourses/Config.php';
    $local  = require __DIR__ .'/configs/local.php';   
    Abc::createApp($config, $local);

 

    
    
    
    
