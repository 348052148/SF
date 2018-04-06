<?php
namespace SF\Controllers;

class BaseController {
    protected $className = self::class;

    protected $plugsLst = [];

    public function pushPlugs($plugs){
        return array_push($this->plugsLst,$plugs);
    }

    public function __call($name, $arguments)
    {
        $plugs = PlugFactory::getPlugs();

        if(!empty($plugs))
        foreach ($plugs as $plug){

            if(!isset($this->plugsLst[$plug])){
                $plugRef = new \ReflectionClass($plug);
                $plugInstance = $plugRef->newInstanceArgs();
                $this->plugsLst[$plug] = $plugInstance;
            }

            if(method_exists($this->plugsLst[$plug],$name)){
               return call_user_func_array([$this->plugsLst[$plug],$name],$arguments);
            }
        }
        return '404';
    }
}