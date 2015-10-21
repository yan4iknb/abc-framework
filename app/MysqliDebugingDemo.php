<?php

namespace ABC\app;

use \ABC\abc as Abc;

class MysqliDebugingDemo
{
    public function __construct()
    {
        $mysqli = Abc::service('Mysqli');
        
        $mysqli->test = true;
        $res = $mysqli->query("SELECT * 
                                 FROM test.test 
                                   WHERE id = 1
                                   AND 1 = 1"
                              );
        
        Abc::dbg($res);        
    }
}

