<?php

    return [ 
              'error_mod'       => 'debug', // Включает дебаггер
              'framework_trace' => true, //Включает в стек дебаггера классы фреймворка              
              'error_language'  => 'Ru', // Перевод ошибок
              
              'mysqli'    =>  ['host'  => 'localhost', 
                               'user'  => 'roots', 
                               'pass'  => '', 
                               'base'  => 'test',
                               'debug' =>  true // Включает режим отладки SQL
                ],
    ];

