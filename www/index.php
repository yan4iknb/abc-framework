<?php

namespace ABC;
/** 
* @TODO To clean in release 
*/
$start = microtime(true);
////////////////////////////////////////


    require __DIR__ .'/../vendor/Abc/Abc.php';  
    $config = require __DIR__ .'/../App/Resourses/Config.php';
    $local  = require __DIR__ .'/configs/local.php';   
    Abc::createApp($config, $local);

/** 
* @TODO To clean in release 
*/
echo '<br /><br />';
echo 'Время генерации страницы: '. sprintf("%01.4f", microtime(true) - $start) .'<br />';
echo 'Количество подключенных файлов: '. count(get_included_files()) .'<br />';
 

    
    
    
    
