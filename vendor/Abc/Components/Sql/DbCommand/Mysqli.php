<?php

namespace ABC\Abc\Components\Sql\DbCommand;

use ABC\Abc\Components\Sql\DbCommand\SqlConstruct;
use ABC\Abc\Components\Sql\DbCommand\Expression;

/** 
 * Конструктор для PDO
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class Mysqli
{
    public $db;
    public $prefix;    

    protected $config;
    protected $command;
    protected $construct;
    protected $stmt;
    protected $query;
    
    /**
    * Конструктор
    *
    */     
    public function __construct($abc, $command = null)
    {
        $this->db = $abc->sharedService('Mysqli');
        $dbType = $abc->getConfig('db_command')['db_type'];
        $this->config  = $abc->getConfig('Mysqli');
        $this->command = $command;        
        $this->construct = new SqlConstruct($this->config['prefix'], $dbType, 'Mysqli');
    }  

    /**
    * Проксирование вызовов методов конструктора
    *
    */     
    public function __call($method, $params)
    { 
        $this->sqlConctruct->$method($params);
        return $this;
    }
    
    
    /**
    * Выполняет запрос из подготовленного выражения с привязкой параметров
    *
    * @param string $sql
    * @param array $params
    *
    * @return object
    */     
    public function createCommand($params)
    {
        $this->construct->disable();
        $this->query = is_array($params) ? $params[0] : $params; 
        $this->query = $this->construct->rescuer->quoteFields($this->query);
        $this->prepare($this->query);
       
        if (!empty($params[1]) && is_array($params[1])) {
            $this->bindValues($params[1]);
        }
     
        return $this->command;
    }

    /**
    * Обертка PDO::prepare()
    *
    * @param string $sql
    *
    * @return object
    */     
    public function prepare($sql)
    {
        $this->stmt = $this->db->prepare($sql);
        return $this->command;        
    }
    
    /**
    * Обертка PDO::bindValue() для массива
    *
    * @param array $params
    *
    * @return object
    */     
    public function bindValues($params)
    {

    } 
    
    /**
    * Обертка PDO::bindValue()
    *
    * @param array $params
    *
    * @return object
    */    
    public function bindValue($params)
    {

    } 
 
    /**
    * Обертка PDO::execute()
    *
    * @return int
    */     
    public function execute()
    {

    } 
    
    /**
    * возвращает набор строк. каждая строка - это ассоциативный массив с именами столбцов и значений.
    * если выборка ничего не вернёт, то будет получен пустой массив.
    *
    * @return array
    */     
    public function queryAll()
    {
        $this->implementQuery();
    }  
    
    /**
    * вернёт один столбец 
    * пустой массив, при отсутствии результата
    *
    * @return mixed
    */     
    public function queryColumn($num = 0)
    {
        if(empty($this->stmt)){
            $this->implementQuery();
        }
    }
    
    /**
    * вернёт одну строку 
    * false, если ничего не будет выбрано
    *
    * @return mixed
    */     
    public function queryRow()
    {
        if(empty($this->stmt)){
            $this->implementQuery();
        }
    }
    
    /**
    * Псевдоним для queryRow()
    *
    * @return mixed
    */     
    public function queryOne()
    {
        return $this->queryRow();
    } 
    
    /**
    * вернёт скалярное значение
    * или false, при отсутствии результата
    *
    * @return mixed
    */     
    public function queryScalar()
    {

    }
    
    /**
    * Удаляет строки
    *
    * @return int
    */     
    public function delete()
    {

        return $this;    
    }
    
    /**
    * Вставляет одну строку
    *
    * @return int
    */     
    public function insert()
    {

        return $this;
    }
    
    /**
    * Вставляет много строк
    *
    * @return int
    */ 
    public function batchInsert()
    {

        return $this;
    }
    
    /**
    * Обновляет данные в строке
    *
    * @return int
    */     
    public function update()
    {

        return $this;
    }
    
    /**
    * Возвращает объект с выражениями
    *
    * @return object
    */     
    public function expression($params)
    {
        return new Expression($params);
    }
    
    /**
    * Старт транзакции
    *
    * @return void
    */     
    public function beginTransaction()
    {

    }
    
    /**
    * COMMIT
    *
    * @return void
    */     
    public function transactionCommit()
    {

    }
    
    /**
    * ROLLBACK
    *
    * @return void
    */     
    public function transactionRollback()
    {

    }
    
    /**
    * Возвращает текст SQL запроса
    *
    * @return string
    */     
    public function getSql()
    {
        return !empty($this->query) ? $this->query : $this->construct->getSql();
    } 
    
    /**
    * Очищает объект для построения нового запроса
    *
    * @return void
    */       
    public function reset()
    {
        $this->query = null;
        $this->construct->reset();  
    } 
    
    /**
    * Тестирует запрос
    *
    * @return object
    */     
    public function test()
    {
        $this->db->test();
        return $this->command;
    }
    
    /**
    * Выполняет SELECT-запросы
    *
    * @return int
    */     
    protected function implementQuery()
    { 
        if (empty($this->query)) {
            $this->query = $this->getSql();
            $this->createCommand($this->query); 
        }
     
        return $this->execute(); 
    } 
    
    /**
    * Подготовка не SELECT-запросов
    *
    * @return void
    */     
    protected function prepareQuery()
    { 
        $this->query = $this->getSql();
        $this->createCommand($this->query); 
    }
}
