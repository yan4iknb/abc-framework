<?php

namespace ABC\app\resourses;


    return [  
              'error_mod'       => 'exception', // Включает дебаггер
              //'framework_trace' => true, //Включает в стек дебаггера классы фреймворка              
              
              'mysqli'    =>  ['host'  => 'localhost', 
                               'user'  => 'root', 
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

