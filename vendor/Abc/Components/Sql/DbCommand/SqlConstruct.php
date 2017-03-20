<?php

namespace ABC\Abc\Components\Sql\DbCommand;

use ABC\Abc\Core\Exception\AbcError;

/** 
 * Конструктор запросов Mysql
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/ 
 */  
class SqlConstruct
{
    public $prefix;
    public $rescuer;    

    protected $disable = false;
    protected $query;    
    protected $sql = [];
    protected $params = [];
    protected $component = ' Component DbCommand: ';
    protected $space = 'ABC\Abc\Components\Sql\DbCommand\\';
    protected $driver;
    
    /**
    * Конструктор
    *
    * @param string config
    */     
    public function __construct($prefix, $dbType, $driver)
    {
        $this->prefix  = $prefix;
        $rescuer = $this->space . $dbType . 'Quote';
        $this->driver  = $driver;dbg($this->driver);
        $this->rescuer = new $rescuer($driver, $prefix); 
    }
    
    /**
    * Устанавливает префикс
    *
    * @param array $params
    */     
    public function setPrefix($params)
    {
        $this->rescuer->newPrefix = $params[0][0];
    }

    /**
    * Удаляет префиксы
    *
    * @param array $params
    */     
    public function unsetPrefix()
    {
        $this->rescuer->prefix = null;
        $this->rescuer->newPrefix = null;
    }
    
    /**
    * Метод оператора SELECT
    *
    * @param array $params
    */     
    public function select($params)
    {
        if (is_array($params)) {
            $params = $params[0];
        }
     
        $this->checkDuble('select');
        
        if (empty($params[0])) {
            $columns = ['*'];
        } elseif (!is_array($params[0])) {
            $columns = preg_split('~\s*,\s*~', trim($params[0]), -1, PREG_SPLIT_NO_EMPTY);
        } elseif (is_array($params[0])) {
         
            foreach ($params[0] as $param) {
             
                if (is_object($param)) {
                    $columns[] = '('. $param->getSql() .')';
                } else {
                    $columns[] = $param;              
                }
            }
         
        } else {
            AbcError::logic($this->component . ABC_COMMAND_SELECT);
        }
       
        $options = !empty($params[1]) ? $params[1] : null;
        $columns = $this->rescuer->wrapFields($columns);
     
        $this->sql['select'] = $options .' '. implode(', ', $columns);
    }
    
    /**
    * Метод оператора SELECT DISTINCT
    *
    * @param array $params
    */     
    public function selectDistinct(...$params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->checkDuble('select');
        $this->checkDuble('select distinct');
        $this->select($params[0]);
        $this->sql['select distinct'] = $this->sql['select'];
        unset($this->sql['select']);
    }
    
    /**
    * Метод оператора FROM
    *
    * @param array $params
    */     
    public function from($params)
    {
        if (is_array($params[0])) {
            $params = $params[0];
        }
 
        $this->isDisable();
        $this->checkParams($params);
        $this->checkSequence('select', 'select distinct', 'delete');
        $this->checkDuble('from');
      
        foreach ($params as $key => $table) {
         
            if (is_string($table) && false === strpos($table, '(')) {
                $table = $this->addAlias($key, $table);  
            } elseif (is_object($table)) {
                $class =  $this->space . $this->driver;
                
                if ($table instanceof $class) {
                    $table = '('. $table->getSql() .') ';
                    $table = $this->addAlias($key, $table);
                } else {
                    AbcError::invalidArgument($this->component . ABC_OTHER_OBJECT);
                }
            }
            
            @$this->sql['from'] .= $table .', ';
        }
        
        $this->sql['from'] = rtrim($this->sql['from'], ', ');
    }

    /**
    * INNER JOIN
    *
    * @param array $params
    *
    */  
    public function join($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->joinInternal('inner join', $params);
    }      
    
    /**
    * LEFT JOIN
    *
    * @param array $params
    *
    */ 
    public function leftJoin($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->joinInternal('left join', $params);
    }    
    
    /**
    * RIGHT JOIN
    *
    * @param array $params
    *
    */ 
    public function rightJoin($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->joinInternal('right join', $params);
    }
    
    /**
    * CROSS JOIN
    *
    * @param array $params
    *
    */ 
    public function crossJoin($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->joinInternal('cross join', $params);
    }    
    
    /**
    * NATURAL JOIN
    *
    * @param array $params
    *
    */ 
    public function naturalJoin($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->joinInternal('natural join', $params);
    }
    
    /**
    * Метод оператора WHERE
    *
    * @param array $params
    */     
    public function where($params)
    {
        $this->isDisable();
        $this->checkDuble('where');
        $this->checkParams($params);
        
        if (!empty($params[1]) && is_array($params[1])) {
         
            foreach ($params[1] as $name => $value) {
                
                if (is_object($value)) {
                    $this->params[$name] = $this->addExpressions($value);
                } else {
                    $this->params[$name] = $this->rescuer->escape($value);                
                }
            }
        } 
        
        if (!empty($params[0])) {
            $this->sql['where'] = $this->conditionsInternal($params[0]);
        } else {
            AbcError::logic($this->component . ABC_SQL_INVALID_CONDITIONS);        
        }    
    }
 
    /**
    * Добавляет условие в существующую часть запроса WHERE с оператором AND
    *
    * @param array $params
    */ 
    public function andWhere($params)
    {
        $this->isDisable(); 
        $this->checkParams($params);
        $this->checkSequence('where');
        $this->addConditions('where', $params, 'and');
    }
    
    /**
    * Добавляет условие в существующую часть запроса WHERE с оператором OR
    *
    * @param array $params
    */ 
    public function orWhere($params)
    {
        $this->isDisable(); 
        $this->checkParams($params);
        $this->checkSequence('where');
        $this->addConditions('where', $params, 'or');
    }
 
    /**
    * Метод оператора HAVING
    *
    * @param array $params
    */     
    public function having($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->checkDuble('having');
     
        if (!empty($params[1]) && is_array($params[1])) {
         
            foreach ($params[1] as $name => $value) {
                $this->params[$name] = $this->rescuer->escape($value);
            }
        }
        
        if (!empty($params[0])) {
            $this->sql['having'] = $this->conditionsInternal($params[0]);
        } else {
            AbcError::logic($this->component . ABC_SQL_INVALID_CONDITIONS);        
        }  
    }
    
    /**
    * Добавляет условие в существующую часть запроса WHERE с оператором AND
    *
    * @param array $params
    */ 
    public function andHaving($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->checkSequence('having');
        $this->addConditions('having', $params, 'and');
    }
    
    /**
    * Добавляет условие в существующую часть запроса WHERE с оператором OR
    *
    * @param array $params
    */ 
    public function orHaving($params)
    {
        $this->isDisable(); 
        $this->checkParams($params);
        $this->checkSequence('having');
        $this->addConditions('having', $params, 'or');
    }

    /**
    * Метод оператора ORDER BY
    *
    * @param array $params
    */      
    public function order($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->checkSequence('select', 'select_distinct', 'from');
        $this->checkDuble('order by');
        $columns = $params[0];
     
        if (is_string($columns) && false !== strpos($columns,'(')) {
            $this->sql['order by'] = $columns;
        } else {
         
            if (!is_array($columns)) {
                $columns = preg_split('~\s*,\s*~', trim($columns), -1, PREG_SPLIT_NO_EMPTY);
            }
            
            foreach ($columns as $i => $column) {
             
                if (is_object($column)) {
                    $columns[$i] = (string)$column;
                } elseif (false === strpos($column,'(')) {
                 
                    if (preg_match('~^(.*?)\s+(asc|desc)$~i', $column, $matches)) {
                        $columns[$i] = $this->rescuer->wrapFields($matches[1]).' '.strtoupper($matches[2]);
                    } else {
                        $columns[$i] = $this->rescuer->wrapFields($column);
                    }
                }
            }
            
            $this->sql['order by'] = implode(', ',$columns);
        }
    }
    
    /**
    * Добавляет параметры в оператор ORDER
    *
    * @param array $params
    */      
    public function addOrder($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->checkSequence('order by');
        $order = $this->sql['order by'];
        unset($this->sql['order by']);
        $this->order($params);
        $this->sql['order by'] = $order .', '. $this->sql['order by'];
    }

    /**
    * Метод оператора GROUP BY
    *
    * @param array $params
    */  
    public function group($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->checkSequence('select', 'select distinct', 'from');
        $this->checkDuble('group by');
        
        if (is_string($params[0]) && false !== strpos($params[0], '(')) {
            $this->sql['group by'] = $params[0];
        } else {
         
            if (!is_array($params[0])) {
                $columns = preg_split('/\s*,\s*/', trim($params[0]), -1, PREG_SPLIT_NO_EMPTY);
            } else {
                $columns = $params[0];
            }
            
            foreach ($columns as $i => $column) {
            
                if (is_object($column)) {
                    $columns[$i] = (string)$column;
                } elseif (false === strpos($column,'(')) {
                    $columns[$i] = $this->rescuer->wrapFields($column);
                }
            }
            
            $this->sql['group by'] = implode(', ',$columns);
        }
    }
    
    /**
    * Добавляет параметры в оператор GROUP BY
    *
    * @param array $params
    */      
    public function addGroup($params)
    {
        $this->isDisable(); 
        $this->checkParams($params);
        $this->checkSequence('group by');
        $order = $this->sql['group by'];
        unset($this->sql['group by']);
        $this->order($params);
        $this->sql['group by'] = $order .', '. $this->sql['group by'];
    }
    

    /**
    * Метод оператора LIMIT
    *
    * @param array $params
    */    
    public function limit($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->checkSequence('select', 'select distinct', 'from');
        $this->checkDuble('limit');
        $this->sql['limit'] = (int)$params[0];
        
        if (!empty($params[1])) {
            $this->sql['offset'] = (int)$params[1];
        }  
    } 
    
    /**
    * Метод оператора OFFSET
    *
    * @param array $params
    */     
    public function offset($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->checkSequence('limit');
        $this->checkDuble('offset'); 
        $this->sql['offset'] = (int)$params[0];
    } 
    
    /**
    * Метод оператора UNION
    *
    * @param array $params
    */  
    public function union($params)
    {
        $this->isDisable();    
        $this->checkParams($params);
        
        if (isset($this->sql['union']) && is_string($this->sql['union'])) {
            $this->sql['union'] = [$this->sql['union']];
        }
        
        $this->sql['union'][] = $params[0];
    } 

    /**
    * Метод оператора DELETE
    *
    * @param array $params
    */  
    public function delete($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->checkDuble('delete from');        
        $this->sql['delete from'] = $this->rescuer->wrapTable($params[0]);
        
        if (!empty($params[2]) && is_array($params[2])) {
         
            foreach ($params[2] as $name => $value) {
                $this->params[$name] = $this->rescuer->escape($value);
            }
        }    
        
        if (!empty($params[1]) && ($where = $this->conditionsInternal($params[1])) != '') {
            $this->sql['delete from'] .= ' WHERE '. $where;
        }
        
        return $this->getSql();
    }

    /**
    * Метод оператора INSERT INTO
    *
    * @param array $params
    */  
    public function insert($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->checkDuble('insert into');
        $this->sql['insert into'] = $this->rescuer->wrapTable($params[0]);
        $this->sql['insert into'] .= "\n    (". implode(', ', $this->rescuer->wrapFields(array_keys($params[1]))) .")";
        $this->values([array_values($params[1])]);
    }
    /**
    * Множественный INSERT
    *
    * @param array $params
    */  
    public function batchInsert($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->checkDuble('insert into');
        $this->sql['insert into'] = $this->rescuer->wrapTable($params[0]);
        $this->sql['insert into'] .= "\n    (". implode(', ', $this->rescuer->wrapFields($params[1])) .")";
        $this->values($params[2]);
    }
    
    /**
    * Метод оператора UPDATE
    *
    * @param array $params
    */  
    public function update($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->checkDuble('update');
        $this->sql['update'] = $this->rescuer->wrapTable($params[0]);
        
        $this->set($params);
     
        if (!empty($params[2])) {
            $params[3] = !empty($params[3]) ? $params[3] : null;           
         
            if (!empty($params[3]) && is_array($params[3])) {
             
                foreach ($params[3] as $name => $value) {
                    $this->params[$name] = $this->rescuer->escape($value);
                }   
            }
            
            $this->where([$params[2], $params[3]]);   
        }
    }

    /**
    * Возвращает текст запроса
    *
    * @return string
    */       
    public function getSql()
    {
        if (empty($this->query)) {
            $this->sql = array_change_key_case($this->sql, CASE_UPPER);
           
            foreach ($this->sql as $operand => $value) {
                
                if (is_array($value)) {
                 
                    foreach ($value as $v) {
                        $this->query .= "\n    ". $operand .' '. $v .' '; 
                    }
                    
                } else {
                    $this->query .= "\n    ". $operand .' '. $value .' ';
                }
            }
        }
        
        return ltrim($this->query, "\n ");
    } 
    
    /**
    * Очищает объект для построения нового запроса
    *
    * @return void
    */       
    public function reset()
    {
        $this->sql = [];
        $this->params = [];
        $this->query = null;
        $this->rescuer->prefix = $this->prefix;
        $this->rescuer->newPrefix = null;
        $this->disable = false;        
    } 
    
    /**
    * Блокирует конструктор
    *
    * @return void
    */       
    public function disable()
    {
        $this->disable = true;
    } 
    
    /**
    * Проверка на блокировку
    *
    * @return void
    */       
    public function isDisable()
    {
        if ($this->disable) {
            AbcError::logic($this->component . ABC_SQL_DISABLE);
        }
    }
    
    /**
    * Метод проверки параметров
    *
    * @param array $operands
    */    
    protected function checkParams()
    {
        $operands = func_get_args();
        
        if (empty($operands[0])) {
            AbcError::logic($this->component . ABC_SQL_EMPTY_ARGUMENTS);        
        }
    }
    
    /**
    * Метод проверки оператора
    *
    * @param array $operands
    */    
    protected function checkSequence()
    {
        $operands = func_get_args();
     
        foreach ($operands as $operand) {
            if (isset($this->sql[$operand])) {
                return true;
            }
        }
        
        AbcError::logic($this->component . ABC_SQL_SEQUENCE);
    } 
    
    /**
    * Метод проверки повтора оператора
    *
    * @param array $operand
    */      
    protected function checkDuble($operand)
    {
        if (isset($this->sql[$operand])) {
            AbcError::logic($this->component . ABC_SQL_DUBLE);
        }
    }
 
    /**
    * 
    *
    * @param string $command
    * @param array  $params
    * @param string $operator
    */ 
    protected function addConditions($command, $params, $operator = null)
    {
        if (!empty($params[1]) && is_array($params[1])) {
         
            foreach ($params[1] as $name => $value) {
                $this->params[$name] = $this->rescuer->escape($value);
            }
        } 
       
        if (!empty($params[0])) {
            $this->sql[$command] = $this->conditionsInternal([$operator, $this->sql[$command], $params[0]]);
        } else {
            AbcError::logic($this->component . ABC_SQL_INVALID_CONDITIONS);        
        }
    }
    
    /**
    * Генерация условий для WHERE
    *
    * @param array $condition
    */  
    protected function conditionsInternal($conditions)
    { 
        if (!is_array($conditions)) {
            return $this->replace($conditions);
        } elseif (empty($conditions)) {
            AbcError::logic($this->component . ABC_SQL_NO_CONDITIONS);    
        }
     
        $operator = strtoupper(array_shift($conditions));
     
        if (count($conditions) < 2) {
            AbcError::logic($this->component . ABC_SQL_COUNT_VALUES);
        }        
        
        if ($operator === 'OR' || $operator === 'AND') { 
          
            foreach ($conditions as &$parts) {
                
                if (is_array($parts)) {
                    $parts = '('. $this->conditionsInternal($parts) .')';
                }
            }
         
            return $this->replace(implode(' '. $operator .' ', $conditions));
            
        } elseif ($operator === 'IN' || $operator === 'NOT IN') {
          
            if (!isset($conditions[0], $conditions[1])) {
                AbcError::logic($this->component . ABC_SQL_INVALID_CONDITIONS);
            }
            
            if (is_array($conditions[1])) {
                $field = $this->rescuer->wrapFields($conditions[0]);
                $values = $conditions[1];
                return $this->replace($field .' '. $operator .' ('. implode(', ', $this->rescuer->escape($values)) .')');
            } else {
                AbcError::logic($this->component . ABC_SQL_INVALID_VALUES);
            }
            
        } elseif ($operator === 'LIKE' || $operator === 'NOT LIKE' || $operator === 'OR LIKE' || $operator === 'OR NOT LIKE') {
         
            if (!isset($conditions[0], $conditions[1])) {
                AbcError::logic($this->component . ABC_SQL_INVALID_CONDITIONS);
            }
            
            if( $operator === 'LIKE' || $operator === 'NOT LIKE') {
                $andor = ' AND ';
            } else {
                $andor = ' OR ';
                $operator = ($operator === 'OR LIKE') ? 'LIKE' : 'NOT LIKE';
            }
         
            if (is_array($conditions[1])) {
                $expressions = [];            
                $field = $this->rescuer->wrapFields($conditions[0]);
                
                foreach ($conditions[1] as $value) {
                    $expressions[] = $field .' '. $operator .' '. $this->rescuer->escape($value);
                }
                
            } else {
                AbcError::logic($this->component . ABC_SQL_INVALID_VALUES);
            }
            
            return $this->replace(implode($andor, $expressions));  
        }
        
        AbcError::logic($this->component . ABC_SQL_INVALID_OPERATOR);
    }
    
    /**
    * Замена плэйсхолдеров значениями
    *
    * @param string|object $subject
    *
    * @return string
    */      
    protected function replace($subject)
    {
        if (is_object($subject)) {
            return $this->addExpressions($subject);
        }
     
        foreach ($this->params as &$value) {
         
            if (is_object($value)) {
                $value = $this->addExpressions($value);
            }
        }
     
        return str_replace(array_keys($this->params), array_values($this->params), $subject);
    }
    
    /**
    * Экранирует и добавляет алиас
    *
    * @param string $key
    * @param string $table
    *
    * @return string
    */  
    protected function addAlias($key, $table)
    {
        if (is_string($key)) {
            $table .= ' '. $this->rescuer->wrapFields($key);
        } 
        
        $alias = null;
     
        if (false === strpos($table, '(')) {
            $a = preg_split('~\s+~', trim($table), -1, PREG_SPLIT_NO_EMPTY);
            $table = !empty($a[0]) ? $a[0] : $table;
            $table = $this->rescuer->wrapTable($table);     
           
            if (!empty($a[2]) && strtoupper($a[1]) == 'AS') {
                $alias = ' AS '. $this->rescuer->wrapFields($a[2]);
            } elseif (!empty($a[1])) {
                $alias = ' '. $this->rescuer->wrapFields($a[1]);
            } 
            
        }
        
        return $table . $alias;
    } 
    
    /**
    * Эмуляция JOIN
    *
    * @param string $type
    * @param array $params
    *
    * @return string
    */ 
    protected function joinInternal($type, $params)
    {
        if (false === strpos($params[0], '(')) {
         
            if (preg_match('~^(.*?)(?i:\s+as|)\s+([^ ]+)$~', $params[0], $matches)) {
                $table = $this->rescuer->wrapTable($matches[1]) .' '. $this->rescuer->wrapFields($matches[2]);
            } else {
                $table = $this->rescuer->wrapTable($params[0]);
            }
            
        } else {
            $table = $params[0];
        }
        
        $conditions = $this->conditionsInternal($params[1]);
        
        if ($conditions != '') {
            $conditions = ' ON '. $conditions;
        }
       
        if (!empty($params[2]) && is_array($params[2])) {
           
            foreach ($params[2] as $name => $value) {
                $this->params[$name] = $value;
            }
        }        
        
        $this->sql[$type][] = $this->replace(' '. $table . $conditions);
    }
    
    /**
    * Метод оператора VALUES
    *
    * @param array $params
    */  
    protected function values($params)
    {
        $this->isDisable();
        $this->checkSequence('insert into');
        $group = '';        
     
        foreach ($params as $values) {
            
            foreach ($values as $name => $value) {
                $values[$name] = '';
                
                if (is_object($value)) { 
                    $values[$name] .= $this->addExpressions($value);
                } else {
                    $values[$name] = $this->rescuer->escape($value);               
                } 
            }
            
            $group .= "\n    (". implode(', ', $values) ."),"; 
        }
        
        $this->sql['values'] = trim($group, ',');
    }
    
    /**
    * Добавляет выражения
    *
    * @param array $value
    *
    * @return string
    */ 
    protected function addExpressions($value)
    {
        $expressions = '';
        $params = $value->getParams();
        $expression = $value->getExpression();
       
        if (!empty($params)) {
         
            foreach ($params as $p => $v) {
                $expressions .= str_replace($p, $this->rescuer->escape($v), $expression);
            }
            
            return $expressions;            
        } 
        
        return $expression;
    }    
    
    
    
    /**
    * Метод оператора SET
    *
    * @param array $params
    */  
    protected function set($params)
    {
        $this->isDisable();
        $this->checkSequence('insert into', 'update');
        
        foreach ($params[1] as $name => $value) {
            $set[$name] = $this->rescuer->wrapFields($name);
           
            if (is_object($value)) { 
                $set[$name] .= ' = '. $this->addExpressions($value);
            } else {
                $set[$name] .= ' = '. $this->rescuer->escape($value);               
            }
        }
        
        $this->sql['set'] = implode(",\n    ", $set); 
    }
}

