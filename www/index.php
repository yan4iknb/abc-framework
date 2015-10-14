<?php

namespace ABC;

    require __DIR__ .'/../vendor/abc/abc.php'; 
    $config = require __DIR__ .'/../versions/app/resourses/config.php';
   
    Abc::createNew($config);
    
    // Так будет запускаться инсталлятор.
    echo Abc::current()->install();
    
    

    
    
    
    
    