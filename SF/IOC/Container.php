<?php
namespace SF\IOC;

class Container {

    private $instances;


    public function bind($abstract, $concrete)
    {
        $this->instances[$abstract] = $concrete;
    }

    public function make($abstract, $parameters = [])
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        return false;
    }
}