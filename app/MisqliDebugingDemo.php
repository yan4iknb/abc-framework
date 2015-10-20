<?php

namespace ABC\app;

use \ABC\abc as Abc;

class MisqliDebugingDemo
{
    public function __construct()
    {
        $mysqli = Abc::component('MySQLi');
        $res = $mysqli->query("SELECT * FROM `city`");
        
        Abc::dbg($res);
    }
}
