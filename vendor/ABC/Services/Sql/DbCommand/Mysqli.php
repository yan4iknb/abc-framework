<?php

namespace ABC\ABC\Services\Sql\DbCommand;

use ABC\ABC\Core\Exception\AbcError;

/** 
 * Конструктор для PDO
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2017
 * @license http://www.wtfpl.net/ 
 */  
class Mysqli
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
    public function __construct($abc, $command = null)
    {
        throw new \Exception($this->component .'<strong>'. __CLASS__ .'()</strong>'. ABC_NO_FUNCTIONAL);
    }  
}
