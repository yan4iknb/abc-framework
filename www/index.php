<?php
/** 
* @TODO To clean in release 
*/
$start = microtime(true);
$startMemory = memory_get_usage();

    require __DIR__ .'/../vendor/Abc/Abc.php';
    $local  = require __DIR__ .'/configs/local.php'; 
    \ABC\Abc::startApp($local);

/** 
* @TODO To clean in release 
*/
?><pre><?php
echo '&nbsp;&nbsp;&nbsp; Время генерации страницы: '. sprintf("%01.4f", microtime(true) - $start) .'<br />';
echo '&nbsp;&nbsp;&nbsp; Количество подключенных файлов: '. count(get_included_files()) .'<br />';
echo '&nbsp;&nbsp;&nbsp; Потребляемая память: '. (memory_get_usage() - $startMemory) . ' bytes<br /><br />';
?></pre><?php    