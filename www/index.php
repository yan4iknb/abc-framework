<?php

    require __DIR__ .'/../vendor/Abc/Abc.php';
    $local  = require __DIR__ .'/configs/local.php';    
    \ABC\Abc::startApp($local);
