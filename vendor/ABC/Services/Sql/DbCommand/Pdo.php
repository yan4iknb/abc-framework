<?php

namespace ABC\Abc\Services\Sql\DbCommand;


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
    public $construct;
    public $rescuer;
    public $prefix;
    public $disable = false;

    protected $abc;
    protected $component = ' Component DbCommand: '; 
    protected $command;

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
        $rescuer  = __NAMESPACE__ .'\\'. $dbType . 'Quote';
        $this->db = $abc->sharedService('Pdo');
        $this->prefix  = $this->db->prefix;    
        $this->command = $command;
        
        $this->rescuer = new $rescuer($this->db, $this->prefix);
        $this->defineConstants();
    }

    /**
    * Смена СУБД
    */  
    public function setDb($config)
    { 
        $this->db = $this->abc->newService('Pdo');
        $this->db->newConnect($config);
    }
   
    /**
    * Устанавливает префикс
    *
    * @param array $params
    */     
    public function setPrefix($prefix)
    {
        $this->rescuer->newPrefix = $prefix;
    }

    /**
    * Удаляет префиксы
    *
    */     
    public function unsetPrefix()
    {
        $this->rescuer->prefix = null;
        $this->rescuer->newPrefix = null;
    } 
    
    /**
    * Общий запрос
    *
    * @param string $sql
    *
    * @return object
    */     
    public function createCommand($sql)
    {
        $this->disable = true;
        $this->sql = $sql; 
        $this->sql = $this->rescuer->quoteFields($this->sql);
        return $this->command;
    }

    /**
    * Обертка PDO::bindValue() для массива
    *
    * @param object $stmt
    * @param array $params
    *
    * @return void
    */     
    protected function bindValuesInternal($stmt, $params)
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
    }

    /**
    * Обертка PDO::execute()
    *
    * @return int
    */     
    public function execute()
    { 
        $sql = $this->getSql();
        $stmt = $this->db->prepare($sql);
     
        $params = $this->command->getParams();        
     
        if (!empty($params)) {
            $this->bindValuesInternal($stmt, $params);
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
    * @param int $style
    *
    * @return array
    */     
    public function queryAll($style = null)
    {
        $style = (!empty($style)) ? $style : \PDO::FETCH_ASSOC;
        $this->executeInternal();       
        return $this->stmt->fetchAll($style);
    }  
    
    /**
    * Вернёт одну строку 
    * false, если ничего не будет выбрано
    *
    * @param int $style
    *
    * @return mixed
    */     
    public function queryRow($style = null)
    { 
        $style = (!empty($style)) ? $style : \PDO::FETCH_ASSOC;
        $this->executeInternal(); 
        return $this->stmt->fetch($style);
    }
    
    /**
    * Вернёт один столбец 
    * пустой массив, при отсутствии результата
    *
    * @param int $num
    *
    * @return mixed
    */     
    public function queryColumn($num = 0)
    {
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
    * Вернёт результат в виде объекта
    *
    * @param string $className
    * @param array $ctorArgs
    *
    * @return mixed
    */     
    public function queryObject($className = null, $ctorArgs = [])
    {        
        $this->sql = $this->getSql();
        $this->executeInternal();    
        return $this->stmt->fetchObject($className, $ctorArgs);
    }
    
    /**
    * Вернёт количество строк текущего запроса
    *
    * @param string $field
    *
    * @return mixed
    */     
    public function count($field = '*')
    {
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
        if (!empty($this->construct)) {
            return $this->construct->getSql();
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
        if (!empty($this->construct)) {
            $this->construct->reset(); 
        }
     
        $this->sql  = null;       
        $this->disable = false;
      
        if (!empty($this->stmt)) { 
            $this->execute = false;
            $this->stmt = null;
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
                $this->bindValuesInternal($this->stmt, $values);
            }  
            
            $this->stmt->execute();
            $this->execute = true;
        }
        
        $this->disable = true; 
    }
    
    /**
    * Выполняет запрос для count()
    *
    * @param string $sql
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
