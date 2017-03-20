<?php

namespace ABC\Abc\Components\Sql\DbCommand;

/** 
 * Кавычки для MYSQL
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class MysqlQuote
{
    public $prefix;
    public $newPrefix;

    protected $driver;

    /**
    * @param string $driver
    */  
    public function __construct($driver, $prefix)
    {
        $this->driver = $driver;
        $this->prefix = $prefix;
    }

    
    /**
    * Экранирует имена таблиц и добавляет префиксы
    *
    * @param string $table
    * @param string $prefix
    *
    * @return string 
    */     
    public function wrapTable($table)
    {
        $base = ''; 
        $table = str_replace('{{%', $this->newPrefix, $table); 
        $table = str_replace(['`', '{{', '}}'], '', $table);            
        $p = explode('.', $table);
     
        if (count($p) > 1) {
            $base  = '`'. $p[0] .'`.';
            $table = $p[1];
        } else {
            $table = $p[0];
        }
        
        return $base .'`'. $this->prefix . $table .'`';
    } 

    /**
    * Экранирует поля
    *
    * @param string|array $fields
    *
    * @return array
    */     
    public function wrapFields($fields)
    {
        if (!is_array($fields)) {
            return $this->quote($fields);
        }
     
        foreach ($fields as $field) {
            $quoteFields[] = $this->quote($field);                
        }
     
        return $quoteFields;
    } 
    
    /**
    * Экранирует значения согласно выбранному драйверу
    *
    * @param string|array $values
    *
    * @return array
    */     
    public function escape($values)
    {
        switch ($this->driver) {
         
            case 'Pdo' :
                $pdo = \ABC\Abc::sharedService('Pdo');
                
                if (!is_array($values)) {
                    return $pdo->quote($values);
                }
                
                foreach ($values as $value) {
                    $result[] = $pdo->quote($value);                
                }
                
            break;
            
            case 'Mysqli' :
                $mysqli = \ABC\Abc::sharedService('Mysqli');
                
                if (!is_array($values)) {
                    return "'". $mysqli->escape_string($values) ."'";
                }
                
                foreach ($values as $value) {
                    $result[] = "'". $mysqli->escape_string($values) ."'";                
                }
                
            break;
        }
        
        return $result;
    }      
    
    /**
    * Заменяет псевдоэкранирование на косые кавычки
    *
    * @param string $sql
    *
    * @return string
    */     
    public function quoteFields($sql)
    {           
        $sql = str_replace('{{%', '{{'. $this->prefix . $this->newPrefix, $sql);
        return str_replace(['[[', ']]', '{{', '}}'], '`', $sql);                
    } 
    
    /**
    * Добавляет алиас
    *
    * @param string $key
    * @param string $field
    * @param bool $table
    *
    * @return string
    */  
    public function addAlias($field, $key = null, $table = false)
    {
        $field = $this->wrapFields($field);
     
        if (is_numeric($key)) {
         
            if (false === strpos($field, '(')) {
                $exp = preg_split('~\s+~', trim($field), -1, PREG_SPLIT_NO_EMPTY);
                $field = !empty($exp[0]) ? $exp[0] : $field;
                $alias = null;
                
                if (!empty($exp[2]) && strtoupper($exp[1]) == 'AS') {
                    $alias = ' AS '. $exp[2];
                } elseif (!empty($exp[1])) {
                    $alias = ' '. $exp[1];
                }                 
                
                return $this->wrapTable($field) .' '. $alias;
            }    
        } 
        
        if (true == $table) {
            return $this->wrapTable($field) .' '. $this->wrapFields($key);     
        }           
        
        return $field .' '. $this->wrapFields($key);
    } 
    
    /**
    * Добавляет алиас к полю
    *
    * @param string $field
    * @param string $key
    *
    * @return string
    */  
    public function addAliasToField($field, $key = null)
    {
        $alias = $this->createAlias($field, $key);
        return $this->wrapFields($field) .' '. $this->wrapFields($alias);
    } 
    
    /**
    * Добавляет алиас к таблице
    *
    * @param string $table
    * @param string $key
    *
    * @return string
    */  
    public function addAliasToTable($table, $key = null)
    {
        $alias = $this->createAlias($table, $key);
        return $this->wrapTable($table) .' '. $this->wrapFields($alias);
    } 
    
    /**
    * Добавляет алиас к выражению
    *
    * @param string $expression
    * @param string $key
    *
    * @return string
    */  
    public function addAliasToExpression($expression, $key = null)
    {
        return $expression .' '. (!empty($key) ? $this->wrapFields($key) : '');
    } 
    
    /**
    * Формирует алиас
    *
    * @param string $string
    * @param string $key
    *
    * @return string
    */  
    protected function createAlias($string, $key = null)
    { 
        if (is_numeric($key)) {
            $alias = null;
            
            if (false === strpos($string, '(')) {
                $exp = preg_split('~\s+~', trim($field), -1, PREG_SPLIT_NO_EMPTY);
              
                if (!empty($exp[2]) && strtoupper($exp[1]) == 'AS') {
                    $alias = ' AS '. $exp[2];
                } elseif (!empty($exp[1])) {
                    $alias = ' '. $exp[1];
                }
            }
            
            return $alias;
        } 
        
        return $key;
    }     
    
    /**
    * Экранирует поля
    *
    * @param string $field
    *
    * @return string
    */     
    protected function quote($field)
    {
        if (false !== strpos($field, '(')){
            return $field;
        } 
        
        $exp = preg_split('~\s+~', trim($field), -1, PREG_SPLIT_NO_EMPTY);  
     
        if (false !== strpos(strtoupper($field), ' AS ')) {
            $field = $exp[0] .'` AS `'. $exp[2];
        } elseif (false !== strpos($field, ' ')) {
            $field = $exp[0] .'` `'. $exp[1];
        }
     
        if (false !== ($pos = strrpos($field, '.'))) {
            $field = substr($field, 0, $pos) .'`.`'. substr($field, $pos + 1);
        }
        
        return '`'. $field .'`';                
    } 
}
