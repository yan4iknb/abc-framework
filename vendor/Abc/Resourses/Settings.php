<?php

namespace ABC\Abc\Resourses;

class Settings
{

    public static function get()
    {
        return [ 
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
                 
                    // Контроллер и экшен по умолчанию
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
                                               . ABC_DS .'www'. ABC_DS .'theme'. ABC_DS .'tpl'. ABC_DS, 
                            // Макет (главный шаблон), если он используется  
                            'layout'          => 'index',
                            // Расширение файлов шаблонов
                            'ext'             => 'tpl',
                            // Запрещает использовать PHP в шаблонах (только для встроенного)
                            'php'             => false,
                                               
                    ],
                    // Конструктор запросов
                    'db_command'    => [
                                            'driver' => 'Pdo'// Драйвер СУБД по умолчанию
                    ],
                    
                    'errors' => [   // Общий уровень ошибок
                                    'error_reporting'  => E_ALL,
                                    // Уровень ошибок для 500 Internal Server Error
                                    'level_500'        => E_ALL & ~E_NOTICE,    
                    ],
                    
                    'debug' => [    // Язык перевода отчета об ошибках
                                    'language'         => 'En', 
                    ],
        ];
    }
}