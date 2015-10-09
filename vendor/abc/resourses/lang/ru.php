<?php

    require_once __DIR__ .'/../vendor/abc/abc.php';    
    include_once __DIR__ .'/../application/configs/main.php';
    
    Abc::createNewAbc()->run();Abc::createNewAbc()->run();
    Abc::work()->error('поперло');
    echo Abc::getVersion();