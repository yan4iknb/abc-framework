<?php

namespace ABC\Abc\Components\Sql\DbCommand;


/** 
 * Класс получения результатов
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/ 
 */  
class Reader
{
    public $db;
    
    protected $stmt;

    /**
    * Конструктор
    *
    */     
    public function __construct($stmt)
    {
        $this->stmt = $stmt;
    }
}

