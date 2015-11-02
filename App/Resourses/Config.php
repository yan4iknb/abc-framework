<?php

namespace ABC\app\resourses;


    return [  
              'error_mod'       => 'debug', // Включает дебаггер
              'framework_trace' => true, //Включает в стек дебаггера классы фреймворка              
              'error_language'  => 'Ru',
              
              'mysqli'    =>  ['host'  => 'localhost', 
                               'user'  => 'roots', 
                               'pass'  => '', 
                               'base'  => 'test',
                               'debug' =>  true // Включает режим отладки SQL
                ],
                
               'pdo'      =>  ['dsn'   => 'mysql:dbname=test;host=localhost;charset=UTF8', 
                               'user'  => 'root', 
                               'pass'  => '',
                               'debug' =>  true // Включает режим отладки SQL
                ],
    ];

