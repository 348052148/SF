<?php
namespace SF\ORM;

/**
 * Orm 基础
 * Class Artemis
 * @package SF\ORM
 */
abstract class Artemis  {

    private $fileds = [];

    private static $signInstance = null;

    protected $databses;

    protected $table;

    public function __construct()
    {
        $this->databses = 'user';
        $this->__setOption();
    }

    abstract public function __setOption();

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
            $artemisRef = new \ReflectionClass(static::class);
            $i = $artemisRef->newInstanceArgs();
            $signclss = ArtemisFactory::artemis();
            $signRef = new \ReflectionClass($signclss);
            self::$signInstance = $signRef->newInstanceArgs([$i->table,$i->databses]);
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
        $artemisRef = new \ReflectionClass(static::class);
        $i = $artemisRef->newInstanceArgs();

        $signclss = ArtemisFactory::artemis();
        $signRef = new \ReflectionClass($signclss);
        self::$signInstance = $signRef->newInstanceArgs([$i->table,$i->databses]);

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