<?php

namespace ABC\Abc\Components\Sql\DbCommand;

use ABC\Abc\Components\Sql\DbCommand\SqlConstruct;

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
    protected $sql;    
    protected $stmt;
    protected $execute = false;
    protected $scalar;
    protected $object;
    protected $count;
    
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
    * Общий запрос
    *
    * @param array $params
    *
    * @return object
    */     
    public function createCommand($params)
    {
        $this->construct->isDisable();
        $this->construct->checkParams($params);
        $this->construct->disable();
        $this->sql = is_array($params) ? $params[0] : $params; 
        $this->sql = $this->construct->rescuer->quoteFields($this->sql);
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
        $values = $this->command->getParams();
     
        if (!empty($values)) {
            $this->bindValues($values);
        }
        
        $this->stmt->execute();
        $this->execute = true;
        return $this->stmt->rowCount();
    }
    
    /**
    * Возвращает набор строк. каждая строка - это ассоциативный массив с именами столбцов и значений.
    * если выборка ничего не вернёт, то будет получен пустой массив.
    *
    * @return array
    */     
    public function queryAll($style = \PDO::FETCH_ASSOC)
    {
        if (is_array($style)) {$style = $style[0];}
        $this->sql = $this->getSql();
        $this->executeInternal();
        return $this->stmt->fetchAll($style);
    }  
    
    /**
    * Вернёт одну строку 
    * false, если ничего не будет выбрано
    *
    * @return mixed
    */     
    public function queryRow($style)
    { 
        $style = (!empty($style) && is_array($style)) ? $style[0] : \PDO::FETCH_ASSOC;
        $this->sql = $this->getSql();
        $this->executeInternal();
        return $this->stmt->fetch($style);
    }
    
    /**
    * Вернёт один столбец 
    * пустой массив, при отсутствии результата
    *
    * @return mixed
    */     
    public function queryColumn($num = 0)
    {
        $num = (!empty($num) && is_array($num)) ? $num[0] : 0;
        $this->sql = $this->getSql();
        $this->executeInternal();
        return $this->stmt->fetchColumn($num);
    }
    
    /**
    * Вернёт скалярное значение
    * или false, при отсутствии результата
    *
    * @return mixed
    */     
    public function queryScalar()
    {
        if (empty($this->scalar)) {
            $this->sql = $this->getSql();
            $this->executeInternal();
            $this->scalar = $this->stmt->fetchColumn();
            $this->stmt->closeCursor();
        }
     
        return $this->scalar;
    }
    
    /**
    * Вернёт результат в иде объекта
    *
    * @return mixed
    */     
    public function queryObject()
    { 
        $params = func_get_args()[0];
        $className = !empty($params[0]) ? $params[0] : null;
        $ctorArgs  = !empty($params[1]) ? $params[1] : [];        
        $this->sql = $this->getSql();
        $this->executeInternal();    
        return $this->stmt->fetchObject($className, $ctorArgs);
    }
    
    /**
    * Вернёт количество строк текущего запроса
    *
    * @return mixed
    */     
    public function count($params = [])
    {
        $field = !empty($params[0]) ? $params[0] : '*';
        $sql = $this->getSql();
        $sql = preg_replace('~^SELECT(.+?)FROM~is', 
                                    'SELECT COUNT('. $field .') FROM', 
                                    $sql); 

        if (empty($this->count)) {
            $stmt = $this->executeCount($sql);
            $this->count = $stmt->fetchColumn();
            $stmt->closeCursor();
        }
     
        return $this->count;
    }

    /**
    * Вставляет одну строку
    *
    * @return object
    */     
    public function insert()
    {
        $this->construct->insert(func_get_args()[0]);
        $this->sql = $this->getSql();
        $this->executeInternal();
        return $this->command;
    }
    
    /**
    * Вставляет несколько строк
    *
    * @return object
    */ 
    public function batchInsert()
    {
        $this->construct->batchInsert(func_get_args()[0]);
        $this->sql = $this->getSql();
        $this->executeInternal();
        return $this->command;
    }
    
    /**
    * Обновляет данные в строке
    *
    * @return object
    */     
    public function update()
    {
        $this->construct->update(func_get_args()[0]);
        $this->sql = $this->getSql();
        $this->executeInternal();
        return $this->command;
    }
    
    /**
    * Удаляет строки
    *
    * @return object
    */     
    public function delete()
    {
        $this->construct->delete(func_get_args()[0]);
        $this->sql = $this->getSql();
        $this->executeInternal();
        return $this->command;    
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
    * Возвращает текущий текст SQL 
    *
    * @return string
    */     
    public function getSql()
    {
        return !empty($this->sql) ? $this->sql : $this->construct->getSql();
    } 
    
    /**
    * Очищает объект для построения нового запроса
    *
    * @return void
    */       
    public function reset()
    {
        $this->sql = null;
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
    * Освобождает ресурсы, выделенные для выполнения текущего запроса
    * 
    * @return void
    */
    public function close()
    {
        $this->stmt->closeCursor();
    }    

    /**
    * Обертка PDO::prepare()
    *
    * @param string $sql
    *
    * @return void
    */     
    protected function prepare($sql)
    {  
        $this->stmt = $this->db->prepare($sql);        
    }
    
    /**
    * Выполняет SELECT-запросы
    *
    * @return void
    */     
    protected function executeInternal()
    {
        if (empty($this->stmt)) {
            $this->sql = $this->construct->rescuer->quoteFields($this->sql);
            $this->prepare($this->sql);
        }
     
        if(false === $this->execute){
            $this->execute();                       
        }
    }
    
    /**
    * Выполняет запрос для count()
    *
    * @return object
    */     
    protected function executeCount($sql)
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt;
    }  
    
    /**
    * Установка констант
    *
    * @return void
    */     
    public function defineConstants()
    {
        defined('ABC_DBCOMMAND') or define('ABC_DBCOMMAND', 'PDO');
        defined('ABC_PARAM_INT') or define('ABC_PARAM_INT', \PDO::PARAM_INT);
        defined('ABC_PARAM_BOOL') or define('ABC_PARAM_BOOL', \PDO::PARAM_BOOL);
        defined('ABC_PARAM_NULL') or define('ABC_PARAM_NULL', \PDO::PARAM_NULL);
        defined('ABC_PARAM_STR') or define('ABC_PARAM_STR', \PDO::PARAM_STR);    
        defined('ABC_PARAM_LOB') or define('ABC_PARAM_LOB', \PDO::PARAM_LOB);
        defined('ABC_PARAM_INPUT_OUTPUT') or define('ABC_PARAM_INPUT_OUTPUT', \PDO::PARAM_INPUT_OUTPUT);  
        defined('ABC_FETCH_ASSOC') or define('ABC_FETCH_ASSOC', \PDO::FETCH_ASSOC);
        defined('ABC_FETCH_NUM') or define('ABC_FETCH_NUM', \PDO::FETCH_NUM);
    }
}
