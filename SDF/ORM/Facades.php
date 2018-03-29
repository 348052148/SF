<?php
namespace SDF\ORM;
class Facades {
    protected static $staticInstance = null;

    public function __construct()
    {
        if(self::$staticInstance == null){
            self::$staticInstance = new self();
        }
    }

    public static function __callStatic($name, $arguments)
    {
        if(self::$staticInstance == null){
            self::$staticInstance = new self();
        }
        return call_user_func_array(array(self::$staticInstance, $name) , $arguments);
    }
}