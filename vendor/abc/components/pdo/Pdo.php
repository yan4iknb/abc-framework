<?php

namespace ABC\Abc\Components\Pdo;

/** 
 * Класс Mysqli
 * 
 * NOTE: Requires PHP version 5.5 or later   
 * @author phpforum.su
 * @copyright © 2015
 * @license http://abc-framework.com/license/ 
 */ 
class Pdo extends \PDO
{
    public $error = null;     
    public $test  = false;
    
    /**
    * @var Dbdebug
    */     
    protected $debugger;

    /**
    * Инициализирует объект Mysqli
    *
    * @return void
    */     
    public function __construct($data = [])
    {
        if (!empty($data)) {
         
            extract($data);
            
            if (!isset($dsn, $user, $pass)) {
                throw new \InvalidArgumentException('Component PDO: wrong data connection in the configuration file', E_USER_WARNING);
            }
            
            $this->debugger = $debugger;
        }
     
        try {
            $this->pdo = parent::__construct($dsn, $user, $pass);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    } 
}