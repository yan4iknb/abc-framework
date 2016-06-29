<?php

namespace ABC\Abc\Components\DbCommand;

use ABC\Abc\Components\DbCommand\Quote;
use ABC\Abc\Core\Exception\AbcError;

/** 
 * Конструктор запросов Mysql
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2016
 * @license http://www.wtfpl.net/ 
 */  
class MysqlConstruct
{
    protected $sql;
    protected $operands = [];
    
    /**
    * Метод оператора SELECT
    *
    * @param array $params
    */     
    public function select($params)
    {
        $this->checkDuble('select');
        
        if (empty($params[0])) {
            $columns = ['*'];
        } elseif (!is_array($params[0])) {
            $columns = preg_split('/\s*,\s*/', trim($params[0]), -1, PREG_SPLIT_NO_EMPTY);
        } else {
            $columns = $params[0];
        }
        
        $options = !empty($params[1]) ? $params[1] : null;
        $columns = Quote::wrap($columns);
        $this->sql = "SELECT ". $options .' '. implode(', ', $columns);
        $this->operands['select'] = true;
    }
    
    /**
    * Метод оператора FROM
    *
    * @param array $params
    */     
    public function from($params)
    {
        $this->check('select');
        $this->checkDuble('from');
        $this->sql .= "\nFROM ";
       
        foreach ($params as $table) {
            $this->sql .= Quote::wrap($table) .',  ';
        }
        
        $this->sql = rtrim($this->sql, ', ');
        $this->operands['from'] = true;
    }
 
    /**
    * Метод оператора WHERE
    *
    * @param array $condition
    */     
    public function where($condition)
    {
        $this->checkDuble('where');
        $this->sql .= "\nWHERE ". $condition[0];
        $this->operands['from'] = true;
    }
    
    /**
    * Метод оператора ORDER BY
    *
    * @param array $params
    */      
    public function orderBy($params)
    {
        $this->check('select');
        $this->check('from');
        $this->checkDuble('orderBy');
        $this->sql .= "\nORDER BY ";
     
        if (!is_array($params[0])) {
            $params = [0 => [$params[0] => $params[1]]];
        }
       
        foreach ($params[0] as $field => $order) {
            $this->sql .= Quote::wrap($field) .' '
                       . (!empty($order) ? $order : 'ASC')
                       .', ';
        }
        
        $this->sql = rtrim($this->sql, ', ');
        $this->operands['orderBy'] = true;
    } 
   
    /**
    * Метод оператора LIMIT
    *
    * @param array $param
    */    
    public function limit($param)
    {
        $this->check('select');
        $this->check('from');
        $this->checkDuble('limit');
        $this->sql .= "\nLIMIT ". (int)$param[0];
        $this->operands['limit'] = true;
    } 
    
    /**
    * Метод оператора OFFSET
    *
    * @param array $param
    */     
    public function offset($param)
    {
        $this->check('limit');
        $this->checkDuble('offset'); 
        $this->sql .= " OFFSET ". (int)$param[0];
        $this->operands['offset'] = true;
    } 
    
    /**
    * Метод проверки оператора
    *
    * @param array $operand
    */    
    public function check($operand)
    {
        if (!isset($this->operands[$operand])) {
            AbcError::logic(' Component DbCommand: '. ABC_SQL_ERROR);
        }
    } 
    
    /**
    * Метод проверки повтора оператора
    *
    * @param array $operand
    */      
    public function checkDuble($operand)
    {
        if (isset($this->operands[$operand])) {
            AbcError::logic(' Component DbCommand: '. ABC_SQL_ERROR);
        }
    }  
    
    /**
    * Возвращает текст запроса
    *
    * @return string
    */       
    public function getSql()
    {
        $this->operands = [];
        return $this->sql;
    }     
}
