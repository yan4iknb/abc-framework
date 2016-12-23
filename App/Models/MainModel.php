<?php

namespace ABC\App\Models;

use ABC\Abc;

/** 
 * Класс MainModel
 * 
 */   
class MainModel
{
    public function getContent()
    {
    
        //$mysqli = Abc::newService('mysqli');
        
        //$mysqli->test(); // Тестируем запрос 
        //$stmt = $mysqli->prepare("INSERT INTO `table`  
                                   //SET `text` = ? " 
                        //); 
                         
        //$stmt->bind_param('d', $text); 
         
        //$text = "6,7";

        //$stmt->execute();
        
        return ['hello' => 'Привет, Мир!'];
    }
}