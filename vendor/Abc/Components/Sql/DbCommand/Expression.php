<?php

namespace ABC\Abc\Components\Sql\DbCommand;

/** 
 * Выражения
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://www.wtfpl.net/ 
 */  
class Expression
{

    protected $expression;
    protected $params = [];
    /**
    * 
    *
    */     
    public function __construct($params)
    {
        $this->extression = $params[0];
        
        if (!empty($params[1]) && is_array($params[1])) {
            $this->params = $params[1];
        }
    }  
    
    /**
    * 
    *
    */     
    public function getExpression()
    {
        return $this->extression;
    } 
    
    /**
    * 
    *
    */     
    public function getParams()
    {
        return $this->params;
    }
}
