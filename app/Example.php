<?php

namespace ABC\App;

class Example
{ 
    public function __construct()
    {        
       throw new \Exception('Тестовое исключение');
        //trigger_error('Полный пипец!!!'); 
    }
}