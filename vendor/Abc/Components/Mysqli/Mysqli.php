<?php

namespace ABC\Abc\Components\Mysqli;

use ABC\Abc\Core\Response;

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
                Response::invalidArgumentException(' Component Mysqli: '. ABC_WRONG_CONNECTION);
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
            Response::logicException(' Component Mysqli: '. $this->connect_error); 
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
            Response::badFunctionCallException('Component Mysqli: '. ABC_NO_SQL_DEBUGGER);
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
