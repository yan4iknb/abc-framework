<?php

namespace ABC\Abc\Components\Sql\DbCommand;

use ABC\Abc\Core\Exception\AbcError;
use ABC\Abc\Components\Sql\DbCommand\Transaction;

/** 
 * Конструктор запросов
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class DbCommand
{
    protected $abc;
    protected $values = [];
    protected $transaction;
    protected $space = 'ABC\Abc\Components\Sql\DbCommand\\';
    protected $driver;
    /**
    * Конструктор
    *
    * @param object $abc
    */     
    public function __construct($abc)
    {
        $this->abc = $abc;
        $driver = $abc->getConfig('db_command')['driver'];
        $this->driver = $this->space . $driver;
        $this->command = new $this->driver($this->abc, $this);
    }
    
    /**
    * Возвращает объект конструктора запроса
    * 
    * @return object
    */     
    public function subQuery()
    {
        return $this->abc->newService('DbCommand');
    }  
  
    /**
    * Проксирование вызовов методов в выбраный драйвер
    *
    */     
    public function __call($method, $param)
    {
        return $this->command->$method($param);
    } 
    
    /**
    * 
    *
    */     
    public function bindParam($name, &$value)
    {
        $this->values[$name] = &$value;
        return $this;
    }  
    
    /**
    * 
    *
    */  
    public function getValues()
    {
        return $this->values;
    }  
    
    /**
    * Старт транзакции
    *
    */
    public function beginTransaction()
    {
        if (empty($this->transaction)) {
            $this->transaction = new Transaction($this->command);
        }
        
        $this->transaction->beginTransaction();
        return $this->transaction;
    }
    
    /**
    * Транзакция
    *
    * @param array $params
    */
    public function transaction(callable $callback)
    {
        $result = false;
        $this->beginTransaction();
        
        try { 
         
            if (!empty($this->transaction)) {
                $result = call_user_func($callback, $this);        
                $this->transaction->commit();
            } else {
                throw new \Exception('DbCommand: ' . ABC_TRANSACTION_EXISTS);
            }
            
        } catch (\Exception $e) {
            $this->transaction->rollback();
            AbcError::logic('DbCommand: ' . ABC_TRANSACTION_ERROR . $e->getMessage());
        } catch (\Throwable $e) {
            $this->transaction->rollback();
            AbcError::logic('DbCommand: ' . ABC_TRANSACTION_ERROR . $e->getMessage());
        }
        
        return $result;
    }    
}
