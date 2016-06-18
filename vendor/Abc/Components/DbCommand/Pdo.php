<?php

namespace ABC\Abc\Components\DbCommand;

use ABC\Abc\Components\DbCommand\Mysql;

/** 
 * Конструктор для PDO
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class Pdo
{
    public $prefix;
    
    protected $db;
    protected $stmt;
    
    /**
    * Конструктор
    *
    */     
    public function __construct($abc)
    {
        $this->db = $abc->sharedService('Pdo');
        $this->prefix = $this->db->prefix;
        $this->mysql = new MysqlConstruct();
    }
    
    /**
    * Конструктор
    *
    */     
    public function __call($method, $params)
    { 
        $this->mysql->$method($params);
        return $this;
    }
    
    /**
    * 
    *
    */     
    public function createCommand($sql = null, $params = [])
    {
        $this->prepare($sql);
        
        if (!empty($params)) {
            $this->bindValues($params);
        }
        
        $this->execute();
        return $this;
    }

    /**
    * 
    *
    */     
    public function prepare($sql)
    {
        $this->stmt = $this->db->prepare($sql);
        return $this;        
    }
    
    /**
    * 
    *
    */     
    public function bindValue($name, $value, $type = PDO::PARAM_STR)
    {
        $this->stmt->bindValue($name, $value, $type);
        return $this;
    } 
    
    /**
    * 
    *
    */     
    public function bindValues($params)
    {
        if (!empty($params)) {
         
            foreach ($params as $name => $param) {
                
                if (is_array($param)) {
                    $value = $param[0];
                    $type  = $param[1];
                } else {
                    $value = $param;
                    $type  = PDO::PARAM_STR;
                }
                
                $this->stmt->bindValue($name, $value, $type);
            }
        }
        
        return $this;
    } 
 
    /**
    * 
    *
    */     
    public function execute()
    {
        $this->stmt->execute();
        return $this;
    } 
    
    /**
    * 
    *
    */     
    public function all()
    {
        $sql = $this->mysql->getSql();
        $this->createCommand($sql);
        return $this->stmt->fetchAll();
    }  
    
    /**
    * 
    *
    */     
    public function column()
    {
        $sql = $this->mysql->getSql();
        $this->createCommand($sql);
        return $this->stmt->fetchColumn();
    }
    
    /**
    * 
    *
    */     
    public function test()
    {
        $this->db->test();
        return $this;
    }    
}
