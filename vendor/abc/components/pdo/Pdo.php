<?php

namespace ABC\Abc\Components\Pdo;

/** 
 * Класс Pdo
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
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
    * Конструктор
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
            
            if (!isset($opt)) {
                $opt = array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                );            
            }
            
            $this->debugger = $debugger;
        }
     
        try {
            @parent::__construct($dsn, $user, $pass, $opt);
        } catch (\PDOException $e) {
         
            if (empty($debugger)) {
                throw $e;
            }
            
            $this->error = $e->getMessage();
        }
    }

    /**
    * Включает тестовый режим
    *
    * @return void
    */     
    public function test()
    {
       $this->test = true;
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
        try {
            $result = parent::query($sql);
        } catch (\PDOException $e) {
         
            if (empty($debugger)) {
                throw $e;
            }
         
            $this->error = $e->getMessage();        
            $result = false;
        } 
        
        if (!empty($this->debugger)) {

            $this->debugger->trace = debug_backtrace();
            $this->debugger->db = $this;
            $this->debugger->component = 'PDO';
            $this->debugger->run($sql, $result);        
        } elseif (empty($this->debugger) && $this->test) {
            throw new \BadFunctionCallException('<b>Component PDO</b>: SQL debugger is inactive. Set to true debug configuration.',
                                                E_USER_WARNING);
        }
        
        if (!$result) {
            throw $e;
        }
        
        return $result;
    } 
    
    /**
    * Обертка для prepare()
    *
    * @param string $sql
    *    
    * @return void
    */     
    public function prepare($sql, $options = null)
    {    
        if (!empty($this->debugger)) {
            return new Shaper($this, $sql);        
        }
        
        return parent::prepare($sql, $options);
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








