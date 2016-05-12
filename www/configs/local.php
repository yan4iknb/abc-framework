<?php

    return [    
                'abc_debug'        => true,
                'framework_trace'  => true,
                
                // Вид URL в ссылках
                'urlManager'  => [
                                    // ЧПУ (Человеко Приятный Урл)
                                    'pretty'      => false,
                                    // Показать/скрыть имя скрипта
                                    'show_script' => false,
                                    // HTTPS
                                    'https'       => false,
                                    // Абсолютный путь
                                    'absolute'    => true,
                ],
                
                'route_rules' => [  '/'                           => 'main/index',
                                    'docs/<paragraph>'            => 'docs/index',
                                    'docs/<paragraph>/<section>'  => 'docs/section',
                ],
                
    ];