<?php

namespace ABC;


    require __DIR__ .'/../vendor/abc/Abc.php'; 
    $config = require __DIR__ .'/../app/resourses/config.php';
   
    Abc::createApp($config);

    new \ABC\app\DebugingDemo;   

    
    

    
    
    
    
    