<?php

/** 
*
* При инициализации класса попытается загрузить файл
* по стандарту PSR-4 (упрощенный вариант, без префиксов)
*
* Находит файлы, начиная от корневой директории сервера
* или от папки с именем, установленном в константе
*
* Возможно использовать префикс ABC (высшее пространство имен).
* Он будет проигнорирован в пути до файла
*
* Пример 1:
*
*      new \ABC\abc\components\Example;
* 
* найдет файл по пути
*
*     ROOT/vendor/abc/components/Example.php
*
* Пример 2:
*
*      new \ABC\app\components\Example;
* 
* подключит файл по пути
*
*     ROOT/version/app/components/Example.php
*
*
* @return void
*/   
    
class Autoloader
{
    protected $basePath;
    protected $repositoryName;
    protected $versionsName;
    
    public function __construct($config)
    {
        $this->basePath = isset($config['base_path']) ? $config['base_path'] : dirname(dirname(__DIR__));
        $this->repositoryName = isset($config['repository_name']) ? $config['repository_name'] : basename(dirname(__DIR__));
        $this->versionsName = isset($config['versions_name']) ? $config['versions_name'] : null;
        
        spl_autoload_register([$this, 'autoload']);
    }

    protected function autoload($class)
    {
        $class = preg_replace('#^ABC\\\#u', '', $class);
     
        $files[] = $this->repositoryName .'/'. $class;    
        $files[] = $this->versionsName . $class;
     
        foreach ($files as $file) {   
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $this->basePath . DIRECTORY_SEPARATOR . $file .'.php');
          
            if(is_readable($file)) {
                include_once $file;
                break;
            }   
        } 
    }
}













