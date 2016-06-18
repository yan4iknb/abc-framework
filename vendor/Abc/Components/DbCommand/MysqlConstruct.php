<?php

namespace ABC\Abc\Components\DbCommand;

use ABC\Abc\Components\DbCommand\Quote;
use ABC\Abc\Core\Exception\AbcError;

/** 
 * Конструктор запросов Mysql
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class MysqlConstruct
{
    protected $sql;
    protected $operands = [];
    /**
    * 
    *
    */     
    public function select($params)
    {
        $this->checkSql('select');
        
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
    * 
    *
    */     
    public function from($params)
    {
        $this->checkSql('from');
        $this->sql .= "\nFROM ";
       
        foreach ($params as $table) {
            $this->sql .= Quote::wrap($table) .',  ';
        }
        
        $this->sql = rtrim($this->sql, ', ');
        $this->operands['from'] = true;
    }
    
    
    /**
    * 
    *
    */     
    public function where($condition)
    {
        $this->checkSql('where');
        $this->sql .= "\nWHERE ". $condition[0];
        $this->operands['from'] = true;
    }
    
    /**
    * 
    *
    */     
    public function orderBy($params)
    {
        $this->checkSql('orderBy');
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
    * 
    *
    */     
    public function limit($param)
    {
        $this->checkSql('limit');
        $this->sql .= "\nLIMIT ". (int)$param[0];
        $this->operands['orderBy'] = true;
    } 
    
    /**
    * 
    *
    */     
    public function offset($param)
    {
        $this->checkSql('offset');
        $this->sql .= ", ". (int)$param[0];
        $this->operands['offset'] = true;
    } 
    
    /**
    * 
    *
    */     
    public function checkSql($operand)
    {
        if (isset($this->operands[$operand])) {
            AbcError::logic(' Component DbCommand: '. ABC_SQL_ERROR);
        }
    } 
    
    /**
    * 
    *
    */     
    public function getSql()
    {
        $this->operands = [];
        return $this->sql;
    }     
}
