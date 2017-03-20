<?php

namespace ABC\Abc\Components\Sql\DbCommand;

use ABC\Abc\Core\Exception\AbcError;
use ABC\Abc\Components\Sql\DbCommand\Transaction;

/** 
 * Конструктор запросов
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/ 
 */  
class DbCommand
{
    protected $abc;
    protected $params = [];
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
    * @return object
    */     
    public function __call($method, $param)
    {
        return $this->command->$method($param);
    } 
    
    /**
    * Связывает значение с параметром по ссылке
    * 
    * @return object
    */     
    public function bindParam($name, &$value)
    {
        $this->params[$name] = &$value;
        return $this;
    }  
    
    /**
    * Возвращает связанные параметры
    * 
    * @return object
    */  
    public function getParams()
    {
        return $this->params;
    }  
    
    /**
    * Старт транзакции
    * 
    * @return object
    */
    public function beginTransaction()
    {
        if (empty($this->transaction)) {
            $this->transaction = new Transaction($this->abc, $this->command);
        }
        
        $this->transaction->beginTransaction();
        return $this->transaction;
    }
    
    /**
    * Транзакция
    *
    * @param callable $callback
    * 
    * @return object
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
