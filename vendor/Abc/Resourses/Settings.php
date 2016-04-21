<?php

namespace ABC\Abc\Resourses;

class Settings
{

    public static function get()
    {
        return [  
                'settings'     => [
                                    'application'     => 'App',
                                    'dir_models'      => 'Models',
                                    'dir_views'       => 'Views',
                                    'dir_controllers' => 'Controllers',
                                    'dir_template'    => dirname(dirname(dirname(__DIR__))) 
                                                       . ABC_DS .'www'. ABC_DS .'theme'. ABC_DS .'tpl'. ABC_DS,
                                    'layout'          => 'index'
                ],       
         
                'default_route' => [
                                    'controller' => 'Main', 
                                    'action'     => 'Index'
                ],
        ];
    }

}