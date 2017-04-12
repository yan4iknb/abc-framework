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
                    
                    // Вид URI в ссылках 
                    'uri_manager'  => [ 
                                // ЧПУ (Человеко Приятный Урл) 
                                'pretty'      => true, 
                                // Показать/скрыть имя скрипта 
                                'show_script' => false, 
                                // HTTPS 
                                'https'       => false, 
                                // Абсолютный путь 
                                'absolute'    => true, 
                    ], 
                    
                    // Конструктор SQL запросов
                    'db_command'    => [
                                            'driver'  => 'Pdo',  // Драйвер СУБД по умолчанию
                                            'db_type' => 'Mysql' // Тип используемой СУБД
                    ],
                    
                    'errors' => [   // Общий уровень ошибок
                                    'error_reporting'  => E_ALL,
                                    // Уровень ошибок для 500 Internal Server Error
                                    'level_500'        => E_ALL,  
                                    'abc_404'          => true, 
                                    'abc_500'          => false, 
                    ],
                    
                    'debug' => [    
                                    'language'         => 'En', // Язык перевода отчета об ошибках
                                    'bugsnare'         => true, //Режим дебаггинга
                    ],
        ];
    }
}