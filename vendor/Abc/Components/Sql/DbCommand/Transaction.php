<?php

namespace ABC\Abc\Components\Sql\DbCommand;

/** 
 * Выражения
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class Transaction
{

    protected $driver;
    
    /**
    * @param string $driver
    */  
    public function __construct($driver)
    {
        $this->driver = $driver;
       
        if (get_class($this->driver->db) === 'Pdo') {
            $this->driver->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); 
        }
    }
    
    /**
    * 
    *
    */     
    public function beginTransaction()
    {
        $this->driver->beginTransaction();
    } 
    
    /**
    * 
    *
    */     
    public function commit()
    {
        $this->driver->commit();
    } 
    
    /**
    * 
    *
    */     
    public function rollback()
    {
        $this->driver->rollback();
    }
}
