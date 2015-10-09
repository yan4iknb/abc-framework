<?php

namespace core\abc;

 /** 
 * Устанавливает путь до репозитария по умолчанию.
 */
    defined('ABC_BASE_PATH') or define('ABC_BASE_PATH', dirname(dirname(dirname(__DIR__))));

 /** 
 * Устанавливает имя директории репозитария по умолчанию.
 */
    defined('ABC_REPOSITORY_NAME') or define('ABC_REPOSITORY_NAME', basename(dirname(dirname(__DIR__))));
    
 /** 
 * Устанавливает имя директории с версиями по умолчанию.
 */
    defined('ABC_VERSIONS_NAME') or define('ABC_VERSIONS_NAME', 'versions');

 /** 
 *
 * При инициализации класса попытается загрузить файл
 * по стандарту PSR-4 (упрощенный вариант, без префиксов)
 *
 * Находит файлы, начиная от корневой директории сервера
 * или от папки с именем, установленном в константе
 *
 * Пример 1:
 *
 *      new \abc\components\Example;
 * 
 * найдет файл по пути
 *
 *     ROOT/vendor/abc/components/Example.php
 *
 * Пример 2:
 *
 *      new \app\components\Example;
 * 
 * подключит файл по пути
 *
 *     ROOT/version/app/components/Example.php
 *
 *
 * @return void
 */   
spl_autoload_register(function ($class) {

    $files[] = ABC_REPOSITORY_NAME .'/'. $class;    
    $files[] = ABC_VERSIONS_NAME .'/'. $class;
 
    foreach ($files as $file) {
        $file = stream_resolve_include_path(ABC_BASE_PATH .'/'. $file .'.php');
      
        if(is_readable($file)) {
            include_once $file;
            break;
        }  
    }     
});

  














