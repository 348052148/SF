<?php
namespace SF\Controllers;

class BaseController {
    protected $className = self::class;
    public function __call($name, $arguments)
    {
        var_dump($this->className.'没有找到'.$name.'方法');
        // TODO: Implement __call() method.
    }
}