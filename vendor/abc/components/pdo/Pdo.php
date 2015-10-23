<?php

namespace ABC\Abc\Components\Pdo;

/** 
 * Класс Mysqli
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */ 
class Pdo 
{
    /**
    * @var Mysqli
    */ 
    public $db;
    public $error = false;     
    public $test  = false;
    
    /**
    * @var Dbdebug
    */     
    protected $debugger;

    /**
    * Инициализирует объект Mysqli
    *
    * @return void
    */     
    public function __construct($data = [])
    {
        if (!empty($data)) {
         
            extract($data);
            
            if (!isset($host, $user, $pass, $base)) {
                throw new \InvalidArgumentException('Component PDO: wrong data connection in the configuration file', E_USER_WARNING);
            }
            
            $this->debugger = $debugger;
        }
     
        $db = @new \PDO($host, $user, $pass, $base);
      
        if ($db->connect_error) {
            $this->error = $db->connect_error;
        }
        
        $db->set_charset("utf8");
        $this->db = $db;
    } 

    /**
    * Обертка для query()
    *
    * @return void
    */     
    public function query($sql)
    {
        $result = $this->db->query($sql);
        
        if (isset($this->debugger) && (false === $result || $this->test)) {
         
            $this->error = $this->db->error;
            $trace = debug_backtrace();
            
            $this->debugger->db = $this->db;
            $this->debugger->type = 'mysqli';
            
            if ($this->test) {
                $this->debugger->testReport($trace, $sql, $this->error);
            }
            else {
                $this->debugger->errorReport($trace, $sql, $this->error);
            }
        }
        
        return $result;
    } 
}