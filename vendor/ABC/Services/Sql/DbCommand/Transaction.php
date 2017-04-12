<?php

namespace ABC\ABC\Services\Sql\DbCommand;

/** 
 * Транзакции
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/ 
 */  
class Transaction
{

    protected $driver;
    protected $class;
    protected $config;
    
    /**
    * Конструктор
    *
    * @param object $abc
    * @param object $abc
    */  
    public function __construct($abc, $driver)
    {
        $this->driver = $driver;
        $this->debug  = $abc->getConfig(strtolower(ABC_DBCOMMAND))['debug'];
    }
    
    /**
    * Стартует транзакцию
    *
    * @return void
    */     
    public function beginTransaction($exception = false)
    {
        if (true === $exception && true === $this->debug && ABC_DBCOMMAND === 'PDO') {
            $this->driver->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); 
        }
     
        $this->driver->beginTransaction();
    } 
    
    /**
    * Фиксирует транзакцию
    *
    * @return void
    */     
    public function commit()
    {
        $this->driver->commit();
        $this->restoreInstallation();
    } 
    
    /**
    * Откат транзакции
    *
    * @return void
    */     
    public function rollback()
    {
        $this->driver->rollback();
        $this->restoreInstallation();
    }
 
    /**
    * Возврат установки дебаггера
    *
    * @return void
    */     
    protected function restoreInstallation()
    {
        if (true === $this->debug && ABC_DBCOMMAND === 'PDO') {
            $this->driver->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING); 
        }
    }    
}