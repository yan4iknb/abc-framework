<?php

namespace ABC\Abc\Services\Sql\Pdo;

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
    
    private $connect = false;

    /**
    * Конструктор
    *
    * @return void
    */     
    public function __construct($abc)
    {
        $this->abc = $abc;
        $config = $abc->getConfig('pdo');
        $this->setMode($config);
    }
    
    /**
    * Новый коннект
    *
    * @param array $config
    * 
    * @return void
    */       
    public function newConnect($config = [])
    {
        if (false === $this->connect) {
            $this->setMode($config);
            $this->connector();
            $this->connect = true;
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
        $this->newConnect();
       
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
        $this->newConnect();
        $stmt = parent::prepare($sql, $options);
        $stmt->rawSql = $sql;
        return $stmt;
    }
    
    /**
    * Обертка для quote()
    *
    * @param string $string
    * @param int $type
    *    
    * @return object
    */     
    public function quote($string, $type = null)
    { 
        $this->newConnect();
        return parent::quote($string, PDO::PARAM_STR);
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
    * Пытается получить имя таблицы и проверить её транзакционность.
    *
    * @param string $sql
    *    
    * @return bool
    */     
    public function checkEngine($sql)
    {    
        $sql = str_replace('`', '', trim($sql)) .' ';
        $sql = str_ireplace(['IGNORE', 'LOW_PRIORITY', 'DELAYED', 'INTO', 'FROM', 'QUICK'], ' ', $sql);
        preg_match('~^[INSERT|UPDATE|DELETE]+?[\s]+(.+?)[\s]+.*~i', $sql, $match);
        
        if (empty($match[1])) {
            return true;
        }
        
        $table = preg_replace('~.*?\.~', '', $match[1]);
       
        $stmt  = $this->rawQuery("SELECT ENGINE 
                                     FROM INFORMATION_SCHEMA.TABLES
                                     WHERE TABLE_NAME =  ". $this->quote($table) 
                                  );
     
        $result = $stmt->fetchColumn();
        
        return  (false === $result || $result === 'InnoDB');
    } 
    
    /**
    * Установка режимов
    *
    * @param array $config
    * 
    * @return void
    */     
    protected function setMode($config)
    {
        $this->config = !empty($config) ? $config : $this->config;  
        $this->prefix = !empty($this->config['prefix']) ? $this->config['prefix'] : null;
        $this->debugger  = !empty($this->config['debug']) ? $this->abc->newService('SqlDebug') : null;
    }
    
    /**
    * Коннектор
    *
    * @return void
    */     
    protected function connector()
    {
        if (!$this->checkConfig($this->config)) {
            return false;
        }
     
        if (empty($this->config['opt'])) {
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];            
        } else {
            $opt = $this->config['opt'];
        }
        
        parent::__construct($this->config['dsn'], $this->config['user'], $this->config['pass'], $opt);
        
        if (!empty($this->config['debug'])) {
            $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, [__NAMESPACE__ .'\Shaper', [$this]]); 
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
        }
    }
    
    /**
    * Проверка корректности настроек
    *
    * @param array $config
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
