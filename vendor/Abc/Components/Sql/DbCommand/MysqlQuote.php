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
    * Экранирует имена таблиц и добавляет префикс
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
    * 
    *
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
    * 
    *
    */     
    public function quoteFields($sql)
    {   
        return str_replace(['[[', ']]'], '`', $sql);                
    } 
    
    /**
    * 
    *
    */     
    protected function quote($field)
    {
        if (false !== strpos($field, '(') || false !== strpos(strtoupper($field), ' AS ')) {
            return $field;
        } 
        
        $table = '';
        
        if (false !== ($pos = strrpos($field, '.'))) {
            $table = '`'. substr($field, 0, $pos) .'`.';
            $field = substr($field, $pos + 1);
        }
        
        if ($field !== '*') {
            $field = '`'. trim($field, '`') .'`';
        }
        
        return $table . $field;                
    } 
}
