<?php

namespace ABC;
/** 
* @TODO To clean in release 
*/
$start = microtime(true);
////////////////////////////////////////

    error_reporting(E_ALL);
    require __DIR__ .'/../vendor/Abc/Abc.php';  
    $config = require __DIR__ .'/../App/Resourses/Config.php';
   
    Abc::createApp($config);

/** 
* @TODO To clean in release 
*/
echo '<br /><br />';
echo 'Время генерации страницы: '. sprintf("%01.4f", microtime(true) - $start) .'<br />';
echo 'Количество подключенных файлов: '. count(get_included_files()) .'<br />';

 \ABC\Abc::dbg(get_included_files());  

    
    
    
    
