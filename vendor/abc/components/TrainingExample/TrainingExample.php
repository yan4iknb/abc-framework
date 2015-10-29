<?php

namespace ABC\Abc\Components\TrainingExample;

/** 
 * Класс TraningExample
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class TrainingExample
{
    /**
    * Конструктор
    *
    * @param array $data
    */     
    public function __construct($data = [])
    {
        // Здесь настройки из конфигурационного файла
    }
    
    public function getText()
    {
        return 'Привет, Мир!';
    }
}
