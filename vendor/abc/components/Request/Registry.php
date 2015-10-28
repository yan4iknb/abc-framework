<?php

namespace ABC\Abc\Request\Registry;

abstract class Registry
{
    abstract protected function get($key);

    abstract protected function set($key, $val);

}