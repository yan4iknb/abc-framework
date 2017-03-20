<?php

namespace ABC\Abc\Components\Sql\DbCommand;

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
    * @param string $driver
    */  
    public function __construct($abc, $driver)
    {
        $this->driver = $driver;
        $class = basename(get_class($this->driver->db));
        $this->config = $abc->getConfig(strtolower($class));
       
        if (true === $this->config['debug'] && $this->class === 'Pdo') {
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
        $this->restoreInstallation();
    } 
    
    /**
    * 
    *
    */     
    public function rollback()
    {
        $this->driver->rollback();
        $this->restoreInstallation();
    }
 
    /**
    * 
    *
    */     
    protected function restoreInstallation()
    {
        if (true === $this->config['debug'] && $this->class === 'Pdo') {
            $this->driver->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING); 
        }
    }    
}
