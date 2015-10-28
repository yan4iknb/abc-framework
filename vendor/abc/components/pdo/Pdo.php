<?php

namespace ABC\Abc\Components\Pdo;

/** 
 * Класс Pdo
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */ 
class Pdo extends \PDO
{
    public $error = null;     
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
            
            if (!isset($dsn, $user, $pass)) {
                throw new \InvalidArgumentException('Component PDO: wrong data connection in the configuration file', E_USER_WARNING);
            }
            
            $this->debugger = $debugger;
        }
     
        try {
            parent::__construct($dsn, $user, $pass);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    /**
    * Обертка для query()
    *
    * @param string $sql
    *
    * @return void
    */     
    public function query($sql)
    {
        $result = parent::query($sql);
       
        if (!empty($this->debugger) && (false === $result || $this->test)) {
         
            $trace = debug_backtrace();
            
            $this->debugger->db = $this;
            $this->debugger->type = 'pdo';
            
            if ($this->test) {
                $this->debugger->testReport($trace, $sql, $this->error);
            } else {
                $this->debugger->errorReport($trace, $sql, $this->error);
            }
            
        } elseif (empty($this->debugger) && $this->test) {
            throw new \BadFunctionCallException('SQL debugger is inactive. Set to true debug configuration.', E_USER_WARNING);
        }
        
        return $result;
    } 
    
    /**
    * Чистый запрос для дебаггера
    *
    * @param string $sql
    *    
    * @return void
    */     
    public function rawQuery($sql)
    {
        return parent::query($sql);
    } 
}








