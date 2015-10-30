<?php

namespace ABC\Abc\Components\Training;

/** 
 * Класс Traning
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class Training
{
    /**
    * @var ABC\Abc\Components\TraningExample\TraningExample
    */     
    public $example;

    /**
    * Конструктор
    *
    * @param array $data
    */     
    public function __construct($data = [])
    {
        // Здесь настройки из конфигурационного файла
    }
    
    public function display()
    {
        echo $this->example->getText();
    }
}
