<?php

    require_once __DIR__ .'/../vendor/abc/abc.php';        
    // Запускаем 
    Abc::createNewAbc();
    // Признаки жизни
    echo Abc::getVersion();
    