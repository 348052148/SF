<?php
namespace SDF\IOC;

abstract class Proxy {
    private $instance;
    public function __construct($ins,$method=[])
    {
        $this->instance = $ins;
    }

    public function __call($name, $arguments)
    {
        $clsRef = new \ReflectionClass($this->instance);
        if($clsRef->hasMethod($name)){
            $methodRef = $clsRef->getMethod($name);
            try {
                $this->beforeAdvice($name);
                $methodRef->invokeArgs($this->instance, $arguments);
                $this->afterAdvice($name);
            }catch (\Exception $exception){
                $this->exceptionAdvice($name);
            }finally{
                $this->finallyAdvice($name);
            }
        }
    }

    public abstract function beforeAdvice($name);

    public abstract function afterAdvice($name);

    public abstract function exceptionAdvice($name);

    public abstract function finallyAdvice($name);

    public abstract function aroundAdvice($name);

}