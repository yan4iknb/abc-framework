<?php

namespace ABC\Abc\Components\Sql\DbCommand;

use ABC\Abc\Components\Sql\DbCommand\SqlConstruct;
use ABC\Abc\Components\Sql\DbCommand\Expression;

/** 
 * Конструктор для PDO
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/ 
 */  
class Pdo
{
    public $db;
    
    protected $command;
    protected $construct;
    protected $stmt;
    protected $query;
    protected $execute = false;
    protected $isCreateCommand = false;
    
    /**
    * Конструктор
    *
    */     
    public function __construct($abc, $command)
    {
        $this->db = $abc->sharedService('Pdo');
        $this->command = $command;        
        $this->construct = new SqlConstruct($abc, 'Pdo');
        $this->defineConstants();
    }
    
    /**
    * Проксирование вызовов методов конструктора
    */     
    public function __call($method, $params)
    { 
        $this->construct->$method($params);
        return $this;
    }
    
    /**
    * Текст для подзапроса
    */  
    public function __toString()
    { 
        return $this->construct->getSql();
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
        $this->construct->isDisable();
        $this->construct->checkParams($params);
        $this->construct->disable();
        $this->query = is_array($params) ? $params[0] : $params; 
        $this->query = $this->construct->rescuer->quoteFields($this->query);
        $values = !empty($params[1]) ? $params[1] : null;
        $this->prepareQuery($values);
        $this->isCreateCommand = true;
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
        foreach ($params as $name => $param) {
         
            if (is_array($param)) {
                $value = $param['value'];
                $type  = $param['type'];
            } else {
                $value = $param;
                $type  = \PDO::PARAM_STR;
            }
            
            if (is_object($value)) {
                $value = $this->construct->createExpressions($value);
                $type  = null; 
            }
            
            $this->stmt->bindValue($name, $value, $type);
        }
       
        return $this->command;
    }

    /**
    * Обертка PDO::execute()
    *
    * @return int
    */     
    public function execute()
    { 
        if (false === $this->isCreateCommand) {
            $this->prepareQuery();
        }
        
        $values = $this->command->getParams();
     
        if (!empty($values)) {
            $this->bindValues($values);
        }
        
        $this->stmt->execute();
        $this->execute = true;
        return $this->stmt->rowCount();
    }
    
    /**
    * Возвращает объект PDOStatement для разбора результа
    *
    * @return array
    */     
    public function query($sql, $params)
    {
        $this->createCommand($sql, $params);
        $this->execute();
        return $this->stmt;
    } 
    
    /**
    * Возвращает набор строк. каждая строка - это ассоциативный массив с именами столбцов и значений.
    * если выборка ничего не вернёт, то будет получен пустой массив.
    *
    * @return array
    */     
    public function queryAll()
    {
        $this->executeForSelect();
        return $this->stmt->fetchAll();
    }  
    
    /**
    * Вернёт один столбец 
    * пустой массив, при отсутствии результата
    *
    * @return mixed
    */     
    public function queryColumn($num = 0)
    {
        $num = !empty($num) ? $num : 0;
        $this->executeForSelect();
        return $this->stmt->fetchColumn($num);
    }
    
    /**
    * Вернёт одну строку 
    * false, если ничего не будет выбрано
    *
    * @return mixed
    */     
    public function queryRow()
    { 
        $this->executeForSelect();
        return $this->stmt->fetch(\PDO::FETCH_NUM);
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
    * Вернёт скалярное значение
    * или false, при отсутствии результата
    *
    * @return mixed
    */     
    public function queryScalar()
    {
        $this->executeForSelect();
        return $this->stmt->fetchColumn();
    }

    /**
    * Вставляет одну строку
    *
    * @return int
    */     
    public function insert()
    {
        $this->construct->insert(func_get_args()[0]);
        return $this->command;
    }
    
    /**
    * Вставляет много строк
    *
    * @return int
    */ 
    public function batchInsert()
    {
        $this->construct->batchInsert(func_get_args()[0]);
        return $this->command;
    }
    
    /**
    * Обновляет данные в строке
    *
    * @return int
    */     
    public function update()
    {
        $this->construct->update(func_get_args()[0]);
        return $this->command;
    }
    
    /**
    * Удаляет строки
    *
    * @return int
    */     
    public function delete()
    {
        $this->construct->delete(func_get_args()[0]);
        return $this->command;    
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
        $this->db->beginTransaction();
    }
    
    /**
    * COMMIT
    *
    * @return void
    */     
    public function commit()
    {
        $this->db->commit();
    }
    
    /**
    * ROLLBACK
    *
    * @return void
    */     
    public function rollback()
    {
        $this->db->rollback();
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
    * Подготовка запросов
    *
    * @param array $values
    *
    * @return void
    */      
    protected function prepareQuery($values = null)
    {
        if (empty($this->query)) {
            $this->query = $this->getSql();
            $this->query = $this->construct->rescuer->quoteFields($this->query);
        }
        
        $this->prepare($this->query); 
      
        if (!empty($values) && is_array($values)) {
            $this->bindValues([$values]);
        } 
    } 
    
    /**
    * Выполняет SELECT-запросы
    *
    * @return int
    */     
    protected function executeForSelect()
    {
        if (empty($this->query)) {
            $this->query = $this->getSql();
            $this->query = $this->construct->rescuer->quoteFields($this->query);
            $this->prepare($this->query);
        }
     
        if(false === $this->execute){
            $this->execute();                       
        }
    }  
    
    /**
    * Обертка PDO::prepare()
    *
    * @param string $sql
    *
    * @return object
    */     
    protected function prepare($sql)
    {
        $this->stmt = $this->db->prepare($sql);
        return $this->command;        
    }
    
    /**
    * Установка констант
    *
    */     
    public function defineConstants()
    {
        defined('ABC_PARAM_INT') or define('ABC_PARAM_INT', \PDO::PARAM_INT);
        defined('ABC_PARAM_BOOL') or define('ABC_PARAM_BOOL', \PDO::PARAM_BOOL);
        defined('ABC_PARAM_NULL') or define('ABC_PARAM_NULL', \PDO::PARAM_NULL);
        defined('ABC_PARAM_STR') or define('ABC_PARAM_STR', \PDO::PARAM_STR);    
        defined('ABC_PARAM_LOB') or define('ABC_PARAM_LOB', \PDO::PARAM_LOB);
        defined('ABC_PARAM_INPUT_OUTPUT') or define('ABC_PARAM_INPUT_OUTPUT', \PDO::PARAM_INPUT_OUTPUT);    
    }
}
