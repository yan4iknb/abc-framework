<?php

namespace ABC\Abc\Components\DbCommand;


/** 
 * Конструктор запросов
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class DbCommand
{
    public $prefix;
    
    /**
    * Конструктор
    *
    * @param object $abc
    *
    */     
    public function __construct($abc)
    {
        $driver = $abc->getConfig('db_command')['driver'];
        $driver = 'ABC\Abc\Components\DbCommand\\'. $driver;
        $this->command = new $driver($abc);
        $this->prefix = $this->command->prefix;
    }
  
    /**
    * 
    *
    */     
    public function __call($method, $param)
    {
        return $this->command->$method($param);
    }  
}
