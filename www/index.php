<?php

namespace ABC;


    require __DIR__ .'/../vendor/abc/abc.php'; 
    $config = require __DIR__ .'/../app/resourses/config.php';
   
    Abc::createNewApp($config);

    new \ABC\app\DebugingDemo;   

    
    

    
    
    
    
    