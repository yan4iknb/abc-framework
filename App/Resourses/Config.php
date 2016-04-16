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
                
              //'abc_template' => 'native',
              
              'route_rule' => [ 'main'                         => 'main/index',
                                'main/<id:\d>'                 => 'main', 
                                'second/<id:\d>'               => 'second'],
              
    ];

