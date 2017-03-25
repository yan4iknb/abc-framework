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
    public $prefix;
    public $disable = false;    

    protected $abc;
    protected $component = ' Component DbCommand: '; 
    protected $space = 'ABC\Abc\Components\Sql\DbCommand\\';
    protected $command;
    protected $construct;
    protected $sql;    
    protected $stmt;
    protected $execute = false;
    protected $scalar;
    protected $count;
    
    /**
    * Конструктор
    *
    */     
    public function __construct($abc, $command)
    {
        $this->abc = $abc;        
     
        $dbType   = $abc->getConfig('db_command')['db_type'];        
        $rescuer  = $this->space . $dbType . 'Quote';
        $this->db = $abc->sharedService('Pdo');
        $this->prefix = $this->db->prefix;    
        $this->command = $command;
        
        $this->rescuer = new $rescuer($this->prefix);
        $this->defineConstants();
    }
    
    /**
    * Проксирование вызовов методов конструктора
    */     
    public function __call($method, $params)
    { 
        $this->getConstruct()->$method($params);
        return $this;
    }

    /**
    * Текст для подзапроса
    */  
    public function __toString()
    { 
        return $this->getConstruct()->getSql();
    } 
    
    /**
    * Возвращает объект с выражениями
    *
    * @return object
    */     
    public function expression($params)
    {
        return new Expression($params[0]);
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
        $this->disable = true;
        $this->sql = $params[0]; 
        $this->sql = $this->rescuer->quoteFields($this->sql);
        return $this->command;
    }

    /**
    * Обертка PDO::bindValue() для массива
    *
    * @param array $params
    *
    * @return object
    */     
    public function bindValues($stmt, $params)
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
                $value = (new Expression())->createExpression($value, $this->rescuer);
                $type  = null; 
            }
            
            $stmt->bindValue($name, $value, $type);
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
        $this->sql = $this->getSql();
        $sql  = $this->rescuer->quoteFields($this->sql);
        $stmt = $this->db->prepare($sql);
     
        $values = $this->command->getParams();        
        
        if (!empty($values)) {
            $this->bindValues($stmt, $values);
        }

        $stmt->execute();
        $cnt = $stmt->rowCount();
        $this->reset();
        return $cnt;
    }
    
    /**
    * Возвращает набор строк. каждая строка - это ассоциативный массив с именами столбцов и значений.
    * если выборка ничего не вернёт, то будет получен пустой массив.
    *
    * @return array
    */     
    public function queryAll($style)
    {
        $style = (!empty($style) && is_array($style)) ? $style[0] : \PDO::FETCH_ASSOC;
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
        $this->getConstruct()->insert(func_get_args()[0]);
        return $this->command;
    }
    
    /**
    * Вставляет несколько строк
    *
    * @return object
    */ 
    public function batchInsert()
    {
        $this->getConstruct()->batchInsert(func_get_args()[0]);
        return $this->command;
    }
    
    /**
    * Обновляет данные в строке
    *
    * @return object
    */     
    public function update()
    {
        $this->getConstruct()->update(func_get_args()[0]);
        return $this->command;
    }
    
    /**
    * Удаляет строки
    *
    * @return object
    */     
    public function delete()
    {
        $this->getConstruct()->delete(func_get_args()[0]);
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
        if (!$this->disable) {
            return $this->getConstruct()->getSql();
        }
        
        return $this->sql; 
    } 
    
    /**
    * Очищает объект для построения нового запроса
    *
    * @return void
    */       
    public function reset()
    {
        if (!empty($this->stmt)) { 
            $this->execute = false;
            $this->stmt = null;
            $this->sql  = null;
            
            if (!$this->disable) {
                $this->getConstruct()->reset(); 
            }
        } 
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
    * Выполняет SELECT-запросы
    *
    * @return void
    */     
    protected function executeInternal()
    {
        if(false === $this->execute){
            $sql = $this->getSql(); 
            $this->stmt = $this->db->prepare($sql);
            $values = $this->command->getParams();        
            
            if (!empty($values)) {
                $this->bindValues($this->stmt, $values);
            }  
            
            $this->stmt->execute();
            $this->execute = true;
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
    * Подключение конструктора
    *
    * @return object
    */     
    protected function getConstruct()
    { 
        if (empty($this->construct)) {
            $this->construct = new SqlConstruct($this, $this->rescuer);
        }
        
        return $this->construct;
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
