<?php

    return [ 
              'error_mod'       => 'debug', // Включает дебаггер
              'framework_trace' => false, //Включает в стек дебаггера классы фреймворка 
              
              'error_language'  => 'Ru', // Перевод ошибок
              
              'url' => ['pretty'      => true, // ЧПУ
                        'absolute'    => false, // Абсолютные ссылки
                        'https'       => false, // HTTPS
                        'show_script' => true, // Покажет скрипт (index.php?param=)
                ],
                
              'mysqli'    =>  ['host'  => 'localhost', 
                               'user'  => 'roots', 
                               'pass'  => '', 
                               'base'  => 'test',
                               'debug' =>  true // Включает режим отладки SQL
                ],
                
              'abc_template' => true, 
    ];

