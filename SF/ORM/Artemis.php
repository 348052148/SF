<?php
namespace SF\ORM;

/**
 * Orm 基础
 * Class Artemis
 * @package SF\ORM
 */
class Artemis  {

    private $fileds = [];

    private static $signInstance = null;

    public $databses;

    public $table;

    public function __construct()
    {
        $this->databses = 'user';
        $this->__setOption();
    }

    public function __setOption()
    {
        $this->databses = 'user';
        $this->table = 'user';
    }

    public function __set($name, $value)
    {
        $this->fileds[$name] = $value;
        // TODO: Implement __set() method.
    }

    public function __get($name)
    {
        if(isset($this->fileds[$name])){
            return $this->fileds[$name];
        }

        return null;
        // TODO: Implement __get() method.
    }

    public function __call($name, $arguments)
    {
        if(self::$signInstance == null){
            self::$signInstance = new MongoArtemis();
        }
        if(method_exists(self::$signInstance,$name)){
            $result =  call_user_func_array(array(self::$signInstance, $name), $arguments);
        }
        if($result==null||is_bool($result)){
            return self::$signInstance;
        }
        return $result;
        // TODO: Implement __call() method.
    }
    //静态
    public static function __callStatic($name, $arguments)
    {
        $a = new self();
        var_dump($a->database);
        self::$signInstance = new MongoArtemis();

        if(method_exists(self::$signInstance,$name)){
            $result =  call_user_func_array(array(self::$signInstance, $name), $arguments);
        }

        if($result==null||is_bool($result)){
            return self::$signInstance;
        }
        return $result;
        // TODO: Implement __callStatic() method.
    }
    public function __invoke()
    {
        // TODO: Implement __invoke() method.
    }


}