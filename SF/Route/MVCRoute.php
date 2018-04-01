<?php
namespace SF\Route;

class MVCRoute extends Route{

    /**
     * 这里注入 query 策略 可以变换策略来影响mvc的规则
     * @var mixed
     * @Resource (name="SFAnnotaQuery")
     */
    protected $queryController;

    public function __construct()
    {
        $this->mapRef = $this->parsePathUrl();
    }


    public function getClassName(){
        return $this->queryController->findController($this->mapRef);
    }

    public function getParames(){
        return $this->queryController->findParames();
    }

    /**
     * 找寻实例
     * @param $classMeta
     */
    public function findInstance(){
        return $this->queryController->getInstance($this->mapRef);
    }

    /**
     * 规则找寻
     * @param $pathinfo
     * @return bool|string
     */
    public function findAction(){
        return $this->queryController->findaAction($this->mapRef);
    }
}