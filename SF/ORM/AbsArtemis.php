<?php
namespace SF\ORM;

class AbsArtemis {

    private $fileds = [];

    private $table;

    private $database;

    private static $signInstance = null;

    public function __construct($table,$database)
    {
        $this->database = $database;
        $this->table = $table;
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

