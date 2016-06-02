<?php

    return [    
                'abc_debug'        => true,
                'framework_trace'  => true,
                
                // Вид URL в ссылках
                'url_manager'  => [
                                    // ЧПУ (Человеко Приятный Урл)
                                    'pretty'      => true,
                                    // Показать/скрыть имя скрипта
                                    'show_script' => false,
                                    // HTTPS
                                    'https'       => false,
                                    // Абсолютный путь
                                    'absolute'    => false,
                ],
                
                //'route_rules' => [  '/'                  => 'main/index',
                                    //'docs/<post>'        => 'main/index',
                                    //'docs/<id:\w+>'      => 'docs/section',
                //],
                
                'template' => [ 
                                    'abc_template'    => true, 
                ], 
                
    ];