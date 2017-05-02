<?php

namespace ABC\ABC\Resourses;

class Settings
{

    public static function get()
    {
        return [ 
         
            // Переменные окружения
            'environment' => include_once 'Environment.php',
            
            // Секция общей конфигурации
            'settings'     => [
                    // Название (или путь до) директории с пользовательскими скриптами  
                    'application'     => 'App',   
                    // Название (или путь до) директории с контроллерами  
                    'dir_controllers' => 'Controllers',  
                    // Название (или путь до) директории с моделями 
                    'dir_models'      => 'Models', 
                    // Название (или путь до) директории со слоем вьюшек  
                    'dir_views'       => 'Views',    
                ],
                
            // Маршрутизация по умолчанию
            'route_rules' => [  
                    '/'   => 'main/index', 
            ], 
                
            'default_route' => [
                    'controller' => 'Main', 
                    'action'     => 'Index' 
            ],
            
            // Секция шаблонизатора
            'template' => [
                    // Шаблонизатор (по умолчанию встроенный)
                    'abc_template'    => true,
                    // Путь до каталога с шаблонами  
                    'dir_template'    => dirname(dirname(dirname(__DIR__)))
                                       . ABC_DS .'www'. ABC_DS .'theme'
                                       . ABC_DS .'tpl'. ABC_DS, 
                    // Макет (главный шаблон), если он используется
                    'layout'          => 'index',
                    // Расширение файлов шаблонов
                    'ext'             => 'tpl',
                    // Запрещает использовать PHP в шаблонах (только для встроенного)
                    'php'             => false,
                                       
            ],
            
            // Вид URI в ссылках 
            'uri_manager'  => [ 
                    // ЧПУ (Человеко Приятный Урл)
                    'pretty'      => true, 
                    // Показать/скрыть имя скрипта
                    'show_script' => false, 
                    // HTTPS 
                    'https'       => false, 
                    // Абсолютный путь 
                    'absolute'    => false, 
            ], 
            
            // Конструктор SQL запросов
            'db_command'    => [
                    'driver'  => 'PDO',  // Драйвер СУБД по умолчанию
                    'db_type' => 'Mysql' // Тип используемой СУБД
            ],
            
            'errors' => [
                    'error_reporting'  => E_ALL,// Общий уровень ошибок
                    'level_500'        => E_ALL & ~E_NOTICE,// Уровень ошибок для 500
                    'abc_404'          => true, 
                    'abc_500'          => false, 
            ],
            
            'debug' => [
                    'language'  => 'Ru', // Язык перевода отчета об ошибках
                    'bugsnare'  => true, //Режим дебаггинга
            ],
        ];
    }
}