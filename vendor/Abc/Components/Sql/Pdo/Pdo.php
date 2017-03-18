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
    protected $shaper = '\ABC\Abc\Components\Sql\Pdo\Shaper';
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
                PDO::ATTR_ERRMODE => (!empty($config['debug']) ? PDO::ERRMODE_WARNING : PDO::ERRMODE_EXCEPTION),
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];            
        } else {
            $opt = $config['opt'];
        }
        
        $this->debugger  = !empty($config['debug']) ? $this->abc->newService('SqlDebug') : null;
        $this->prefix = !empty($config['prefix']) ? $config['prefix'] : null; 
     
        parent::__construct($config['dsn'], $config['user'], $config['pass'], $opt);
        
        if (!empty($config['debug'])) {
            $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, [$this->shaper, [$this]]); 
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
        if (!empty($this->debugger)) {
         
            if (false === $this->checkEngine($sql)) {
                AbcError::logic(' Component PDO: '. ABC_NO_SUPPORT);
                return false;
            }
         
            $result = @parent::query($sql);     
            $this->debugger->error = $this->errorInfo()[2];
            $this->debugger->trace = debug_backtrace();
            $this->debugger->db = $this;
            $this->debugger->component = 'PDO';
            $this->debugger->run($sql, $result);        
        } elseif (empty($this->debugger) && $this->test) {
            AbcError::badFunctionCall('Component PDO: '. ABC_NO_SQL_DEBUGGER);
        } else {
            $result = parent::query($sql);
        }
        
        return $result;
    } 
    
    /**
    * Обертка для prepare()
    *
    * @param string $sql
    * @param array $options
    *    
    * @return object
    */     
    public function prepare($sql, $options = [])
    {    
        $stmt = parent::prepare($sql, $options);
        $stmt->rawSql = $sql;
        return $stmt;
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
    * Пытается получить имя таблицы.
    *    
    * @return bool
    */     
    public function checkEngine($sql)
    {    
        $sql = str_replace('`', '', trim($sql)) .' ';
        $sql = str_ireplace(['IGNORE', 'LOW_PRIORITY', 'DELAYED', 'INTO', 'FROM', 'QUICK'], ' ', $sql);
        preg_match('~^[INSERT|UPDATE|DELETE]+?[\s]+(.+?)[\s]+.*~i', $sql, $match); 
        $table = preg_replace('~.*?\.~', '', $match[1]);
       
        $stmt  = $this->rawQuery("SELECT ENGINE 
                                     FROM INFORMATION_SCHEMA.TABLES
                                     WHERE TABLE_NAME =  ". $this->quote($table) 
                                  );
     
        $result = $stmt->fetchColumn();
        
        return  (false === $result || $result === 'InnoDB');
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
