<?php

namespace ABC\abc\components\mysqli;

class Mysqli 
{
    public $db;
    protected $host;    
    protected $user;    
    protected $pass;
    protected $base;    
    
    public function __construct($data = [])
    {        
        if (!empty($data)) {
         
            extract($data);
            
            if (!isset($host, $user, $pass, $base)) {
                trigger_error('Wrong data connection in the configuration file', E_USER_WARNING);
            }
            
            $this->newConnect($host, $user, $pass, $base);
        }
    }
    
    public function newConnect($host = '', $user = '', $pass = '', $base = '')
    {
        if (empty($host) || empty($user) || empty($base)) {
            trigger_error('Incorrect data connect', E_USER_WARNING);
        }
        
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->base = $base;
        $this->connect();
    }  
    
    public function connect()
    {
        $this->db = @new \Mysqli($this->host, $this->user, $this->pass, $this->base);
      
        if ($this->db->connect_error) {
            trigger_error('<b>MySQLi error:</b> '. $this->db->connect_error, E_USER_WARNING);
        }
    }      
}