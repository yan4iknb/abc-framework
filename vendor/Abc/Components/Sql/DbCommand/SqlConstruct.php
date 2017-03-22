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
   
    protected $component = ' Component DbCommand: '; 
    protected $space = 'ABC\Abc\Components\Sql\DbCommand\\';
    protected $operators = ['=', '!=', '>', '<', '>=', '<=', '<>', '<=>', '!<', '!>']; // NOT, IS NULL
    protected $disable = false;
    protected $query;    
    protected $sql = [];
    protected $params = [];
    protected $driver;
    
    /**
    * Конструктор
    *
    * @param string config
    */     
    public function __construct($abc, $driver)
    {
        $dbType  = $abc->getConfig('db_command')['db_type'];
        $prefix  = $abc->getConfig('pdo')['prefix'];
        $rescuer = $this->space . $dbType . 'Quote';
        $this->driver  = $driver;
        $this->rescuer = new $rescuer($driver, $prefix, $this->component); 
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
        $this->isDisable();
        $this->checkDuble('select');
        $this->checkDuble('select distinct');        
     
        if (is_array($params)) {
            $params = $params[0];
        }
        
        $options = !empty($params[1]) ? $params[1] : null;
        $params =  !empty($params[0]) ? $params[0] : null;
        $columns = $this->normaliseColumns($params);       
     
        $this->sql['select'] = $options .' '. implode(', ', $columns);
    }
    
    /**
    * Добавляет параметров к SELECT
    *
    * @param array $params
    */     
    public function addSelect($params)
    {
        $this->isDisable();
        $this->checkSequence('select', 'select distinct', 'update');
        
        $params = !empty($params[0]) ? $params[0] : null;
        $columns = $this->normaliseColumns($params);
        $this->sql['select'] .= ', '. implode(', ', $columns);
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
        $from = '';
        
        foreach ($params as $key => $table) {
         
            if (is_string($table) && false === strpos($table, '(')) {
                $table = $this->rescuer->addAliasToTable($table, $key);
            } elseif (is_object($table)) {
                $class =  $this->space . $this->driver;
                
                if ($table instanceof $class) {
                    $table = '('. $table->getSql() .') ';
                    $table = $this->rescuer->addAliasToTable($table, $key);
                } else {
                    AbcError::invalidArgument($this->component . ABC_OTHER_OBJECT);
                }
            }
            
            $from .= $table .', ';
        }
        
        $this->sql['from'] = rtrim($from, ', ');
    }

    /**
    * JOIN
    *
    * @param array $params
    *
    */  
    public function join($params)
    {
        $this->isDisable();
        $this->checkSequence('select', 'select distinct', 'update');
        $this->checkParams($params);
        $type = strtolower(array_shift($params));
        $this->joinInternal($type, $params);
    }      
    
    /**
    * INNER JOIN
    *
    * @param array $params
    *
    */  
    public function innerJoin($params)
    {
        $this->isDisable();
        $this->checkSequence('select', 'select distinct', 'update');
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
        $this->checkSequence('select', 'select distinct', 'update');
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
        $this->checkSequence('select', 'select distinct', 'update');
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
        $this->checkSequence('select', 'select distinct', 'update');
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
        $this->checkSequence('select', 'select distinct', 'update');
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
        //$params[0]
     
        if (!empty($params[1]) && is_array($params[1])) {
         
            foreach ($params[1] as $name => $value) {
                
                if (is_object($value)) {
                    $this->params[$name] = $this->createExpressions($value);
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
        $this->createConditions('where', $params, 'and');
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
        $this->createConditions('where', $params, 'or');
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
                
                if (is_object($value)) {
                    $this->params[$name] = $this->createExpressions($value);
                } else {
                    $this->params[$name] = $this->rescuer->escape($value);                
                }
            }
        }         
       
        if (!empty($params[0])) {
            $this->sql['having'] = $this->conditionsInternal($params[0]);
        } else {
            AbcError::logic($this->component . ABC_SQL_INVALID_CONDITIONS);        
        }  
    }
    
    /**
    * Добавляет условие в существующую часть запроса HAVING с оператором AND
    *
    * @param array $params
    */ 
    public function andHaving($params)
    {
        $this->isDisable();
        $this->checkParams($params);
        $this->checkSequence('having');
        $this->createConditions('having', $params, 'and');
    }
    
    /**
    * Добавляет условие в существующую часть запроса HAVING с оператором OR
    *
    * @param array $params
    */ 
    public function orHaving($params)
    {
        $this->isDisable(); 
        $this->checkParams($params);
        $this->checkSequence('having');
        $this->createConditions('having', $params, 'or');
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
        $this->sql['group by'] = $this->prepareGroupOrder($params[0]);
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
        $group = $this->sql['group by'];
        unset($this->sql['group by']);
        $this->group($params);
        $this->sql['group by'] = $group .', '. $this->sql['group by'];
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
        $this->sql['order by'] = $this->prepareGroupOrder($params[0]);
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
        $this->checkSequence('select', 'select_distinct', 'from');
        $order = $this->sql['order by'];
        unset($this->sql['order by']);
        $this->order($params);
        $this->sql['order by'] = $order .', '. $this->sql['order by'];
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
        $this->checkSequence('select', 'select distinct', 'from', 'update', 'insert', 'delete');
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
        
        if (is_array($params[0])) {
         
            foreach ($params[0] as $sql) {
                $this->addUnion($sql);           
            }
            
        } else {
            $this->addUnion($params[0]); 
        }
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
        $table = $this->rescuer->wrapTable($params[0]);
        $this->sql['insert into'] = $table;
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
            $this->where([$params[2], $params[3]]);
        }
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
      
        if (!empty($params[1])) {
            $params[2] = !empty($params[2]) ? $params[2] : null;         
            $this->where([$params[1], $params[2]]);
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
    public function checkParams()
    {
        $operands = func_get_args();
       
        if (empty($operands[0])) {
            AbcError::logic($this->component . ABC_SQL_EMPTY_ARGUMENTS);        
        }
    }
    
    /**
    * Метод проверки последовательности операторов
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
    * создает условие для WHERE, HAVING и ON
    *
    * @param string $command
    * @param array  $params
    * @param string $operator
    */ 
    protected function createConditions($command, $params, $operator = null)
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
    * Генерация условий
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
        
        if ($operator === 'AND' || $operator === 'OR') { 
            return $this->conditionsAnd($conditions, $operator);   
        } elseif ($operator === 'IN' || $operator === 'NOT IN') { 
            return $this->conditionsIn($conditions, $operator);  
        } elseif ($operator === 'LIKE' || $operator === 'NOT LIKE' || $operator === 'OR LIKE' || $operator === 'OR NOT LIKE') {   
            return $this->conditionsLike($conditions, $operator); 
        } elseif (in_array($operator, $this->operators)) {   
            return $this->conditionsOther($conditions, $operator); 
        }
        
        AbcError::logic($this->component . ABC_SQL_INVALID_OPERATOR);
    }
   
    /**
    * Генерация условий с операторами группы AND
    *
    * @param array $condition
    */  
    protected function conditionsAnd($conditions, $operator)
    { 
        foreach ($conditions as &$parts) {
            
            if (is_array($parts)) {
                $parts = '('. $this->conditionsInternal($parts) .')';
            }
        }
     
        return $this->replace(implode(' '. $operator .' ', $conditions));
    }
    
    /**
    * Генерация условий с операторами группы IN 
    *
    * @param array $conditions
    * @param string $operator
    *
    * $return string
    */   
    protected function conditionsIn($conditions, $operator)
    { 
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
    }
    
    /**
    * Генерация условий с операторами группы LIKE
    *
    * @param array $conditions
    * @param string $operator
    *
    * $return string
    */  
    protected function conditionsLike($conditions, $operator)
    { 
        if (count($conditions) < 2) {
            AbcError::logic($this->component . ABC_SQL_INVALID_CONDITIONS);
        }        
        
        if( $operator === 'LIKE' || $operator === 'NOT LIKE') {
            $andor = ' AND ';
        } else {
            $andor = ' OR ';
            $operator = ($operator === 'OR LIKE') ? 'LIKE' : 'NOT LIKE';
        }
        
        $expressions = [];            
        $field = $this->rescuer->wrapFields($conditions[0]);
     
        if (is_array($conditions[1])) {
         
            foreach ($conditions[1] as $value) {
                $expressions[] = $field .' '. $operator .' '. $this->rescuer->escape($value);
            }
            
        } elseif (is_string($conditions[1])) {
            $expressions[] =  $field .' '. $operator .' '. $this->rescuer->escape($conditions[1]);
        } else {
            AbcError::logic($this->component . ABC_SQL_INVALID_VALUES);            
        }
     
        return $this->replace(implode($andor, $expressions));  
    }
   
    /**
    * Генерация условий с другими операторами
    *
    * @param array $conditions
    * @param string $operator
    *
    * $return string
    */  
    protected function conditionsOther($condition, $operator)
    {
        $field = $this->rescuer->wrapFields($condition[0]);
        $value = $condition[1];
        return $this->replace($field .' '. $operator .' '. $value);
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
            return $this->createExpressions($subject);
        }
     
        foreach ($this->params as &$value) {
         
            if (is_object($value)) {
                $value = $this->createExpressions($value);
            }
        }
     
        return str_replace(array_keys($this->params), array_values($this->params), $subject);
    }
    
    /**
    * Подготавливает колонки
    *
    * @param array $params
    *
    * @return string|array
    */     
    protected function normaliseColumns($params)
    {
        if (empty($params)) {
            return ['*'];
        } 
        
        if (is_string($params)) {
            $columns = preg_split('~\s*,\s*~', trim($params), -1, PREG_SPLIT_NO_EMPTY);
            return $this->rescuer->wrapFields($columns);
        } 
        
        if (is_array($params)) {
         
            foreach ($params as $key => $param) {
             
                if (is_object($param)) {
                    $columns[] = $this->rescuer->addAliasToExpression($this->createExpressions($param), $key);
                } else {
                    $columns[] = $this->rescuer->addAliasToField($param, $key);              
                }
            }
            
            return $columns;            
        } 
        
        if (is_object($params)) {
            return $this->rescuer->addAliasToExpression($this->createExpressions($params));
        } 
        
        AbcError::logic($this->component . ABC_COMMAND_SELECT);
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
        if (!is_string($params[0])) {
            AbcError::logic($this->component . ABC_SQL_INVALID_CONDITIONS);
            return false;
        }
        
        $table = $this->rescuer->wrapTable($params[0]);
        $conditions = '';
       
        if (!empty($params[1])) {
         
            if (is_string($params[1]) && false === strpos($params[0], '(')) {
             
                if (preg_match('~^(.*?)(?i:\s+as|)\s+([^ ]+)$~', $params[0], $matches)) {
                    $table = $this->rescuer->wrapTable($matches[1]) .' '. $this->rescuer->wrapFields($matches[2]);
                }
             
                $conditions = $this->rescuer->wrapOn($params[1]); 
                
            } elseif (is_array($params[1])) {               
                $conditions = ' ON ('. $this->conditionsInternal($params[1]) .')';
            } elseif (is_object($params[1])) {
                $conditions = ' ON ('. $this->createExpressions($params[1]) .')';
            } else {
                AbcError::logic($this->component . ABC_SQL_INVALID_VALUES);
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
                    $values[$name] .= $this->createExpressions($value);
                } else {
                    $values[$name] = $this->rescuer->escape($value);               
                } 
            }
            
            $group .= "\n    (". implode(', ', $values) ."),"; 
        }
        
        $this->sql['values'] = trim($group, ',');
    }
    
    /**
    * Обработка выражений ORDER BY и GROUP BY
    *
    * @param mixed $values
    *
    * @return string
    */      
    protected function prepareGroupOrder($values)
    {
        if (is_string($values) && false !== strpos($values,'(')) {
            return $values;
        } elseif(is_string($values)) {  
            $exp = preg_split('~\s*,\s*~', trim($values), -1, PREG_SPLIT_NO_EMPTY);
            return $this->normaliseGroup($exp);  
        } elseif (is_array($values)) {
            return $this->normaliseGroup($values); 
        } elseif (is_object($values)) {
            return $this->createExpressions($values);
        } 
        
        AbcError::logic($this->component . ABC_SQL_INVALID_CONDITIONS);        
    }  
    
    
    /**
    * Генерация выражений ORDER BY и GROUP BY
    *
    * @param mixed $values
    *
    * @return string
    */      
    protected function normaliseGroup($values)
    {
        foreach ($values as $direction => $column) {
         
            if (is_object($column)) {
                $columns[] = $this->createExpressions($column);
            } elseif (is_string($direction)) {
                $columns[] = $this->rescuer->wrapFields($column) .' '. strtoupper($direction);
            } elseif (false === strpos($column,'(') && !is_string($direction)) {
             
                if (preg_match('~^(.*?)\s+(asc|desc)$~i', $column, $matches)) {
                    $columns[] = $this->rescuer->wrapFields($matches[1]) .' '. strtoupper($matches[2]);
                } else {
                    $columns[] = $this->rescuer->wrapFields($column);
                }
            }
        }
       
        return implode(', ', $columns);
    }
    
    /**
    * Добавляет выражения
    *
    * @param object $object
    *
    * @return string
    */ 
    protected function createExpressions($object)
    {
        $class =  $this->space . 'Expression';
        
        if ($object instanceof $class) {
            $expressions = '';
            $params = $object->getParams();
            $expression = $object->getExpression();
           
            if (!empty($params)) {
             
                foreach ($params as $p => $v) {
                    
                    if (is_object($v)) {
                        $expressions .= str_replace($p, '('. $v .')', $expression);
                    } else {
                        $expressions .= str_replace($p, $this->rescuer->escape($v), $expression);                    
                    }
                }
                
                return $expressions;            
            } 
         
            return $expression;            
        } 
        
        AbcError::invalidArgument($this->component . ABC_OTHER_OBJECT);
    }    
    
    /**
    * Добавляет часть запроса в UNION
    *
    * @param array $sql
    */  
    protected function addUnion($sql)
    {
        if (isset($this->sql['union']) && is_string($this->sql['union'])) {
            $this->sql['union'] = ["\n    ". $this->sql['union']];
        }
        
        $this->sql['union'][] = "\n    ". $sql;
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
                $set[$name] .= ' = '. $this->createExpressions($value);
            } else {
                $set[$name] .= ' = '. $this->rescuer->escape($value);               
            }
        }
        
        $this->sql['set'] = implode(",\n    ", $set); 
    }
}

