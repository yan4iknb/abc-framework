<?php

    require __DIR__ .'/../vendor/Abc/Abc.php';
    $local  = require __DIR__ .'/configs/local.php';
    try{    
        \ABC\Abc::startApp($local);
    } catch (\ErrorException $e) {
        echo $e->getCode();
    } 