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
class Mysqli extends \Mysqli
{

    public $test = false;
    
    /**
    * @var ABC\Abc\Components\Sqldebug\SqlDebug
    */     
    protected $debugger;

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
                throw new \InvalidArgumentException('<b>Component Mysqli</b>: wrong data connection in the configuration file', 
                                                    E_USER_WARNING);
            }
            
            $this->debugger = $debugger;    
        }
     
        parent::__construct($host, $user, $pass, $base);
        
        if (!$this->connect_error) {
            $this->set_charset("utf8");
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
            throw new \BadFunctionCallException('<b>Component Mysqli</b>: SQL debugger is inactive. Set to true debug configuration.',
                                                E_USER_WARNING);
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
