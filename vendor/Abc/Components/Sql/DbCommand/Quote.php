<?php

namespace ABC\Abc\Components\Sql\DbCommand;

/** 
 * Ковычки
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class Quote
{
    
    /**
    * 
    *
    */     
    public static function wrap($fields)
    {
        if (!is_array($fields)) {
            return self::single($fields);
        }
     
        foreach ($fields as $field) {
            $quoteFields[] = self::single($field);                
        }
     
        return $quoteFields;
    }     
    
    /**
    * 
    *
    */     
    protected static function single($field)
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
