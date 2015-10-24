<?php

namespace ABC\Abc\Components\Mysqli;

/** 
 * Класс Mysqli
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */  
class Mysqli extends \Mysqli
{

    public $test = false;
    
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
                throw new \InvalidArgumentException('Component Mysqli: wrong data connection in the configuration file', E_USER_WARNING);
            }
            
            $this->debugger = $debugger;
        }
     
        parent::__construct($host, $user, $pass, $base);
      
        if ($this->connect_error) {
            $db->set_charset("utf8");
        }
    } 

    /**
    * Обертка для query()
    *
    * $param string $sql
    *
    * @return void
    */     
    public function query($sql)
    {
        $result = parent::query($sql);
       
        if (isset($this->debugger) && (false === $result || $this->test)) {
         
            $trace = debug_backtrace();
            
            $this->debugger->db = $this;
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
    
    /**
    * Чистый запрос для дебаггера
    *
    * $param string $sql
    *    
    * @return void
    */     
    public function rawQuery($sql)
    {
        return parent::query($sql);
    }     
}





