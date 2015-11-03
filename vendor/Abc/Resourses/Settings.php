<?php

namespace ABC\Abc\Resourses;

class Settings
{

    public static function get()
    {
        return [  
                'settings'     => [
                                    'application'     => 'App',
                                    'dir_controllers' => 'Controllers',
                                    'dir_models'      => 'Models',
                                    'dir_views'       => 'Views',
                                    'dir_template'    => dirname(dirname(dirname(__DIR__))) 
                                                       . ABC_DS .'www'. ABC_DS .'theme'. ABC_DS .'tpl'. ABC_DS,
                                    'layout'          => 'index'
                ],       
         
                'defaultRoute' => [
                                    'controller' => 'Main', 
                                    'action'     => 'Index'
                ],
        ];
    }

}