<?php

namespace ABC\abc\components\container;

class Container 
{
    protected $container;

    public function __construct()
    {
        $this->container = new \ABC\abc\core\ServiseLocator;
    }
}