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
    protected $driver;
    protected $abc;
    
    /**
    * Конструктор
    *
    * @param object $abc
    */     
    public function __construct($abc)
    {
        $this->abc = $abc;
        $driver = $abc->getConfig('db_command')['driver'];
        $this->driver = 'ABC\Abc\Components\DbCommand\\'. $driver;
        $this->command = new $this->driver($this->abc);
        $this->prefix = $this->command->prefix;
    }
    
    /**
    * Возвращает объект конструктора запроса
    * 
    * @return object
    */     
    public function subQuery()
    {
        return new $this->driver($this->abc);
    }  
  
    /**
    * Проксирование вызовов методов конструктора
    *
    */     
    public function __call($method, $param)
    {
        return $this->command->$method($param);
    }  
}
