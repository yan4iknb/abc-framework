<?php

namespace ABC\Abc\Components\Mysqli;

/** 
 * Класс Mysqli
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class Mysqli extends \mysqli
{

    public $test = false;
    public $host;    
    public $user;    
    public $pass;    
    public $base;
    
    /**
    * @var ABC\Abc\Components\Sqldebug\SqlDebug
    */     
    public $debugger;

    /**
    * Конструктор
    *
    * @param array $data
    *
    */     
    public function __construct($data = [])
    {
        if (!empty($data)) {
         
            extract($data);
           
            if (!isset($host, $user, $pass, $base)) {
                trigger_error(ABC_INVALID_ARGUMENT_EX 
                             .' Component Mysqli: '. ABC_WRONG_CONNECTION, 
                              E_USER_WARNING);
            } else {
                $this->host = $host;
                $this->user = $user;
                $this->pass = $pass;
                $this->base = $base;
                defined('ABC_DBPREFIX') or define('ABC_DBPREFIX', @$prefix);
                $this->newConnect();
            }
        }
    }
    
    /**
    * Коннектор
    *
    * @return void
    */     
    public function newConnect()
    {
        parent::__construct($this->host, $this->user, $this->pass, $this->base); 
        
        if ($this->connect_error) {
            trigger_error(ABC_LOGIC_EX 
                         .' Component Mysqli: '. $this->connect_error, 
                          E_USER_WARNING); 
            return false;
        }
        
        $this->set_charset("utf8");
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
    * @param int $resultMode
    *
    * @return object
    */     
    public function query($sql, $resultMode = null)
    {
        $result = parent::query($sql, $resultMode);
        
        if (!empty($this->debugger)) {
            $this->debugger->trace = debug_backtrace();
            $this->debugger->db = $this;
            $this->debugger->component = 'Mysqli';
            $this->debugger->run($sql, $result);        
        } elseif (empty($this->debugger) && $this->test) {
            trigger_error(ABC_BAD_FUNCTION_CALL_EX 
                         .'Component Mysqli: '. ABC_NO_SQL_DEBUGGER,
                          E_USER_NOTICE);
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
    public function prepare($sql)
    {    
        if (!empty($this->debugger)) {
            return new Shaper($this, $sql);        
        }
        
        return parent::prepare($sql);
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
