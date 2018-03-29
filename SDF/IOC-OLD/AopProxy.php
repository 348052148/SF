<?php
namespace SDF\IOC;

class AopProxy extends Proxy{
    private $poincut;
    public function __construct($ins,$poincut)
    {
        parent::__construct($ins);

//        var_dump($poincut);
        $this->poincut = $poincut;
    }

    public  function afterAdvice($name)
    {
        foreach ($this->poincut as $method => $pct){
            if($method == '$' || $method == $name){
                foreach ($pct as $p){
                    if(!isset($p['after']) || !is_string($p['after'])) continue;
                    $classInfo = explode('.',$p['after']);
                    $clsRef = new \ReflectionClass($classInfo[0]);
                    $ins = $clsRef->newInstance();
                    $method = $clsRef->getMethod($classInfo[1]);
                    $method->setAccessible(true);
                    $method->invokeArgs($ins, []);
                }

            }
        }
    }

    public  function beforeAdvice($name)
    {
        foreach ($this->poincut as $method => $pct){
            if($method == '$' || $method == $name){
                foreach ($pct as $p){
                    if(!isset($p['before']) || !is_string($p['before'])) continue;
                    $classInfo = explode('.',$p['before']);
                    $clsRef = new \ReflectionClass($classInfo[0]);
                    $ins = $clsRef->newInstance();
                    $method = $clsRef->getMethod($classInfo[1]);
                    $method->setAccessible(true);
                    $method->invokeArgs($ins, []);
                }

            }
        }
    }

    public  function exceptionAdvice($name)
    {
        foreach ($this->poincut as $method => $pct){
            if($method == '$' || $method == $name){
                foreach ($pct as $p){
                    if(!isset($p['afterthrowing']) || !is_array($p['afterthrowing'])) continue;
                    $classInfo = explode('.',$p['afterthrowing']);
                    $clsRef = new \ReflectionClass($classInfo[0]);
                    $ins = $clsRef->newInstance();
                    $method = $clsRef->getMethod($classInfo[1]);
                    $method->setAccessible(true);
                    $method->invokeArgs($ins, []);
                }

            }
        }
    }

    public  function finallyAdvice($name)
    {

    }

    public  function aroundAdvice($name)
    {
        
    }
}