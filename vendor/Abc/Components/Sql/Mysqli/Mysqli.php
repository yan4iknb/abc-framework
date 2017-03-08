<?php

namespace ABC\Abc\Components\Sql\Mysqli;

use ABC\Abc\Core\Exception\AbcError;

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
    public $prefix;
    public $test = false;
  
    protected $abc;   
    protected $debugger;

    /**
    * Конструктор
    *
    * @param object $abc
    *
    */     
    public function __construct($abc)
    {
        $this->abc = $abc;
        $config = $abc->getConfig('mysqli');
     
        if (!empty($config)) {
            $this->newConnect($config);  
        } else {
            AbcError::invalidArgument(' Component Mysqli: '. ABC_WRONG_CONNECTION);
        }
    }
    
    /**
    * Коннектор
    *
    * @return void
    */     
    public function newConnect($config = [])
    {
        if (!$this->checkConfig($config)) {
            return false;
        }
     
        parent::__construct($config['host'], $config['user'], $config['pass'], $config['base']); 
        
        $this->debugger = !empty($config['debug']) ? $this->abc->newService('SqlDebug') : null;    
        $this->prefix = @$config['prefix']; 
        
        if ($this->connect_error) {
            AbcError::logic(' Component Mysqli: '. $this->connect_error); 
            return false;
        }
        
        $this->set_charset("utf8");
    }
    
    /**
    * Включает тестовый режим
    *
    * @return object
    */     
    public function test()
    {
       $this->test = true;
       return $this;
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
            Response::badFunctionCallError('Component Mysqli: '. ABC_NO_SQL_DEBUGGER);
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
    
    /**
    * Проверка корректности настроек
    *
    * @param string $config
    *    
    * @return bool
    */     
    protected function checkConfig($config = [])
    {
        extract($config);
        
        if (!isset($host, $user, $pass, $base)) {
            AbcError::invalidArgument(' Component Mysqli: '. ABC_WRONG_CONNECTION);
            return false;
        }
        
        return true;
    }  
}
