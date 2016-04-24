<?php

namespace ABC\Abc\Components\Pdo;

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
    
    /**
    * @var Dbdebug
    */     
    protected $debugger;

    /**
    * Конструктор
    *
    * @return void
    */     
    public function __construct($abc)
    {
        $config = $abc->getFromStorage('config');
        $data = @$config['pdo'] ?: null;
        
        if (!empty($data)) {
         
            extract($data);
            
            if (!isset($dsn, $user, $pass)) {
                AbcError::invalidArgument(' Component PDO: '. ABC_WRONG_CONNECTION);
            }
            
            if (!isset($opt)) {
                $opt = array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_Error,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                );            
            }
            
            defined('ABC_DBPREFIX') or define('ABC_DBPREFIX', @$prefix);
            $this->debugger = $debugger;
        }
     
        try {
            @parent::__construct($dsn, $user, $pass, $opt);
        } catch (\PDOError $e) {
         
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
        } catch (\PDOError $e) {
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








