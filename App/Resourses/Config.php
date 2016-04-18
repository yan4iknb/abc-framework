<?php

    return [ 
              'error_mod'       => 'debug', // Включает дебаггер
              'framework_trace' => true, //Включает в стек дебаггера классы фреймворка 
              
              'error_language'  => 'Ru', // Перевод ошибок
              
              'url' => ['pretty'      => true, // ЧПУ
                        'absolute'    => false, // Абсолютные ссылки
                        'https'       => false, // HTTPS
                        'show_script' => false, // Покажет скрипт (index.php?param=)
                ],
                
              'mysqli'    =>  ['host'  => 'localhost', 
                               'user'  => 'roots', 
                               'pass'  => '', 
                               'base'  => 'test',
                               'debug' =>  true // Включает режим отладки SQL
                ],
                
              //'abc_template' => false, // Выключает встроенный шаблонизатор
              
              // Правила роутинга
              'route_rules' => [ 
                                'main'                         => 'main/index',
                                '<id:\d+>'                     => 'main/index',
                                '<delete>'                     => 'main/index',
                                'main/<id:\d+>'                => 'main/index',
                                'main/<id:\d+>/<num:\d+>'      => 'main/index',
                                'main/<id:\d+>/<param>'        => 'main/index',
                                'main/<edit>'                  => 'main/index',
                                'main/<edit>/<num:\d+>'        => 'main/index',                               
                                   
                ],
              
    ];

