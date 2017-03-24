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
    public function __construct($extression)
    {
        $this->extression = $extression;
      
        if (!empty($params[1]) && is_array($params[1])) {
            foreach ($params[1] as $name => $value) {
                $this->params[$name] = $value;
            }
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
