<?php

namespace ABC\app;

use \ABC\abc as Abc;

class MisqliDebugingDemo
{
    public function __construct()
    {
        $mysqli = Abc::component('MySQLi');
        
        $mysqli->test = true;
        $res = $mysqli->query("SELECT * 
                                 FROM test.test 
                                   WHERE id = 1
                                   AND 1 = 1"
                              );
        
        Abc::dbg($res);
    }
}

        //$res = $mysqli->query("SELECT * 
                                 //FROM test.test 
                                   //WHERE id = 1
                                   //AND 1 = 1"
                              //);