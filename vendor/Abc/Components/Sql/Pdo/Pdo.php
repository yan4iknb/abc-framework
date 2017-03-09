<?php

namespace ABC\Abc\Components\Sql\Pdo;

use ABC\Abc\Components\Sql\Pdo\Shaper;
use ABC\Abc\Core\Exception\AbcError;

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
  
    protected $abc;
    protected $debugger;

    /**
    * Конструктор
    *
    * @return void
    */     
    public function __construct($abc)
    {
        $this->abc = $abc;
        $config = $abc->getConfig('pdo');
        
        if (!empty($config)) {
            $this->newConnect($config);  
        } else {
            AbcError::invalidArgument(' Component PDO: '. ABC_WRONG_CONNECTION);
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
     
        if (!isset($config['dsn'], $config['user'], $config['pass'])) {
            AbcError::invalidArgument(' Component PDO: '. ABC_WRONG_CONNECTION);
            return false;
        }
        
        if (empty($config['opt'])) {
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];            
        } else {
            $opt = $config['opt'];
        }
        
        $this->debugger  = !empty($config['debug']) ? $this->abc->newService('SqlDebug') : null;
        $this->prefix = !empty($config['prefix']) ? $config['prefix'] : null; 
     
        try {
            parent::__construct($config['dsn'], $config['user'], $config['pass'], $opt);
        } catch (\PDOException $e) {
            AbcError::invalidArgument($e->getMessage());
        }  
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
    *
    * @return void
    */     
    public function query($sql)
    {
        try {
            $result = parent::query($sql);
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();        
            $result = false;
        } 
        
        if (!empty($this->debugger)) {
         
            $this->debugger->trace = debug_backtrace();
            $this->debugger->db = $this;
            $this->debugger->component = 'PDO';
            $this->debugger->run($sql, $result);        
        } elseif (empty($this->debugger) && $this->test) {
            AbcError::badFunctionCall('Component PDO: '. ABC_NO_SQL_DEBUGGER);
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
    public function prepare($sql, $options = [])
    {    
        if (!empty($this->debugger)) {
            new Shaper($this, $sql);
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
        
        if (!isset($dsn, $user, $pass)) {
            AbcError::invalidArgument(' Component PDO: '. ABC_WRONG_CONNECTION);
            return false;
        }
        
        return true;
    }
}
