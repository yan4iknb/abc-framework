<?php

namespace ABC\Abc\Components\Sql\DbCommand;

use ABC\Abc\Core\Exception\AbcError;
use ABC\Abc\Components\Sql\DbCommand\Expression;
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
    protected $params;
    protected $transaction;
    protected $space = 'ABC\Abc\Components\Sql\DbCommand\\';
    protected $component = ' Component DbCommand: ';
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
        $driver = $this->space . $driver;
        $this->driver = new $driver($this->abc, $this);
    }
  
    /**
    * Проксирование вызовов методов в выбраный драйвер
    * 
    * @return object
    */     
    public function __call($method, $params)
    {
        if (method_exists($this->driver, $method)) {
            call_user_func_array([$this->driver, $method], $params);
            return $this;
        } else {
         
            if (empty($this->construct)) {
                $this->construct = new SqlConstruct($this->driver);
                $this->driver->construct = $this->construct;
            }
         
            call_user_func_array([$this->construct, $method], $params);
            return $this;
        }
    }
    
    /**
    * Возвращает объект с выражениями
    *
    * @param $term
    *
    * @return object
    */     
    public function expression($term)
    {
        return new Expression($term);
    }
    
    /**
    * Возвращает новый объект конструктора запроса
    * 
    * @return object
    */     
    public function subQuery()
    {
        return $this->abc->newService('DbCommand');
    }  

    /**
    * Связывает значение с параметром 
    * 
    * @return object
    */     
    public function bindValue($name, $value, $dataType = null)
    {
        if (!empty($dataType)) {
            $this->params[$name]['value'] = $value;
            $this->params[$name]['type']  = $dataType;
        } else {
            $this->params[$name] = $value;
        }
        return $this;
    }  
    
    /**
    * Связывает список значений с параметрами
    * 
    * @return object
    */     
    public function bindValues($values)
    {
        foreach ($values as $name => $value) {
         
            if ($name == 0) {
                AbcError::invalidArgument($this->component . ABC_ERROR_BINDVALUES);
            }  
            
            $dataType = null;
         
            if (is_array($value)) {
                $dataType = key($value);
                $value = array_shift($value);
            }
         
            $this->bindValue($name, $value, $dataType);
        }
      
        return $this;
    }  
    
    /**
    * Связывает значение с параметром по ссылке
    * 
    * @return object
    */     
    public function bindParam($name, &$value, $dataType = null)
    {
        if (!empty($dataType)) {
            $this->params[$name]['value'] = &$value;
            $this->params[$name]['type']  = $dataType;
        } else {
            $this->params[$name] = &$value;
        }
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
            $this->transaction = new Transaction($this->abc, $this->driver);
        }
        
        $this->transaction->beginTransaction(true);
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
            throw $e;
        } catch (\Throwable $e) {
            $this->transaction->rollback();
        }
        
        return $result;
    }
}
