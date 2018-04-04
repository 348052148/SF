<?php
namespace SF\ORM;

/**
 * Orm 提供对象的操作方式，不仅仅是对象的操作方式
 * Class Artemis
 * @package SF\ORM
 */
abstract class Artemis  {

    private $fileds = [];

    private static $signInstance = null;

    private static $thisInstance = null;

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
        if($name == 'id'){
            $this->fileds['_id'] = $value;
        }else{
            $this->fileds[$name] = $value;
        }

        // TODO: Implement __set() method.
    }

    public function save(){
        if(isset($this->id)){
            return $this->findAndUpdate(['_id'=>$this->id],$this->fileds);
        }else{
            $this->id =  $this->insert($this->fileds);
        }

    }

    public function find(){
        $this->fileds = $this->first([])->toArray();
        return $this;
    }

    public function __get($name)
    {
        if(isset($this->fileds['_id'])&&$name == 'id'){
            return $this->fileds['_id'];
        }
        if(isset($this->fileds[$name])){
            return $this->fileds[$name];
        }

        return null;
        // TODO: Implement __get() method.
    }

    public function __call($name, $arguments)
    {
        if(self::$thisInstance == null){
            $artemisRef = new \ReflectionClass(static::class);
            self::$thisInstance = $artemisRef->newInstanceArgs();
        }
        if(self::$signInstance == null){

            $signclss = ArtemisFactory::artemis();
            $signRef = new \ReflectionClass($signclss);
            self::$signInstance = $signRef->newInstanceArgs([self::$thisInstance->table,self::$thisInstance->databses]);
        }
        if(method_exists(self::$signInstance,$name)){
            $result =  call_user_func_array(array(self::$signInstance, $name), $arguments);
        }

        if($result === false) return false;

        if($result==null||is_bool($result)){
            return self::$thisInstance;
        }
        return $result;
        // TODO: Implement __call() method.
    }
    //静态
    public static function __callStatic($name, $arguments)
    {
        $artemisRef = new \ReflectionClass(static::class);
        self::$thisInstance = $artemisRef->newInstanceArgs();

        $signclss = ArtemisFactory::artemis();
        $signRef = new \ReflectionClass($signclss);
        self::$signInstance = $signRef->newInstanceArgs([self::$thisInstance->table,self::$thisInstance->databses]);

        if(method_exists(self::$signInstance,$name)){
            $result =  call_user_func_array(array(self::$signInstance, $name), $arguments);
        }

        if($result === false) return false;

        if($result==null||is_bool($result)){
            return self::$thisInstance;
        }
        return $result;
        // TODO: Implement __callStatic() method.
    }


    public function __invoke()
    {
        // TODO: Implement __invoke() method.
    }


}