<?php
namespace SDF\Core;

use SDF\Annotations\ClassAnnotation;
use SDF\Annotations\MethodAnnotation;
use SDF\IOC\Summer;
use SDF\IOC\SummerFactory;

abstract class BaseApplication implements Application{

    protected $app_path = 'application';

    protected $controller_suffix = 'Controller';

    protected $controller_namespace = 'controllers';

    protected $current_controller = '';

    protected $current_action = '';

    protected $current_parames = [];

    protected $controller_instance;

    protected $parames = [];

    public function __construct($appPath = 'application',$namespace='controllers')
    {
        $this->app_path = $appPath;
        $this->controller_namespace = $namespace;
        //加载并合并自定义注入配置
        $di_dirs = [
            $this->getAppliactionPath().'/controllers',
            $this->getAppliactionPath().'/dao',
            $this->getAppliactionPath().'/services',
            $this->getAppliactionPath().'/aop',
        ];
        $this->summer = SummerFactory::getAutoContext($di_dirs);
    }

    public function getAppliactionPath(){
        return $this->app_path;
    }

    public function setController($controller){
        $this->current_controller = $controller;
    }

    public function getController(){
        return $this->current_controller;
    }

    public function setAction($action){
        $this->current_action = $action;
    }

    public function getAction(){
        return $this->current_action;
    }

    public function setParames($parames){
        $this->current_parames = $parames;
    }

    public function getParames(){
        return $this->current_parames;
    }

    public function setControllerInstance($instance){
        $this->controller_instance = $instance;
    }

    public function getControllerInstance(){
        return $this->controller_instance;
    }

    // 初始化加载
    public function load()
    {

    }

    public function loadBaseUrl(){

        $mapRef = Route::parseUrl();

        $this->setController($mapRef['controller'].$this->controller_suffix);

        $this->setAction($mapRef['action']);

        $this->setParames($mapRef['parames']);
    }

    private function parsePath($pathinfo,$parttern){
        //每次匹配清理值

        if(substr($pathinfo,0,1) == '/'){
            $pathinfo = substr($pathinfo,1);
        }
        if(substr($parttern,0,1) == '/'){
            $parttern = substr($parttern,1);
        }

        $Lparttern = explode('/',$pathinfo);
        $Rparttern = explode('/',$parttern);

        $rcount = count($Rparttern);

        for ($i=0;$i<count($Lparttern);$i++){
            if($i >= $rcount){
                return false;
            }
            // 如果是参数模式 则直接跳过
            if(preg_match('/^{\w+}$/',$Rparttern[$i])){
                array_push($this->parames,$Lparttern[$i]);
                continue;
            }
            if($Lparttern[$i] != $Rparttern[$i] ){
                return false;
            }
        }
        return true;
    }

    private function findAction($class,$pathinfo){
        $classRef = new \ReflectionClass($class);
        $methodLst = $classRef->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methodLst as $method){

                $ants = $this->summer->parseMethod($method);

                foreach ($ants as $ant){
                    if($ant['method'] == 'Route'){
                        $req_method = isset($ant['parame']['method'])?trim($ant['parame']['method']):'GET';
                        if($this->parsePath($pathinfo,$ant['parame']['value']) && strtoupper($_SERVER['REQUEST_METHOD'])==strtoupper($req_method) ){
                            return $method->getName();
                        }
                    }
                }

        }
        return false;
    }

    public function run()
    {
        // 加载路由文件 实现从注解加载
        try {
            $mapRef = Route::parseBaseUrl();

            $this->setController($mapRef['class'] . $this->controller_suffix);

            $aciton = $this->findAction($this->controller_namespace . '\\' . $this->getController(),$mapRef['pathinfo']);

            if ($aciton == false) {
                throw new \Exception('不存在正确的路由映射');
            }

            $this->setAction($aciton);

            $this->setParames($this->parames);
        }catch (\Exception $exception){
            var_dump('404');die;
            $this->loadBaseUrl();
        }finally{
            $this->doRun();
        }
    }

    public abstract function doAction();

    //执行
    public function doRun()
    {
        // 设置容器核心
        $this->setControllerInstance($this->summer->get(lcfirst($this->getController())));

        $this->doAction();
    }
}