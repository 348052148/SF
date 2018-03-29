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

    public function run()
    {
        // 加载路由文件 实现从注解加载
        try {
            $mapRef = Route::parseBaseUrl();

            $this->setController($mapRef['class'] . $this->controller_suffix);

            // 类注解器 获取controller
            $classAnnotation = new ClassAnnotation($this->controller_namespace . '\\' . $this->getController());

            if (!$classAnnotation->isController()) {
                throw new \Exception('此类不是控制器');
            }

            // 方法注解器 获取action
            $methodAnnotation = new MethodAnnotation($this->controller_namespace . '\\' . $this->getController());

            $aciton = $methodAnnotation->findAction($mapRef['pathinfo']);

            $parames = $methodAnnotation->getParames();

            if ($aciton == false) {
                throw new \Exception('不存在正确的路由映射');
            }

            $this->setAction($aciton);

            $this->setParames($parames);
        }catch (\Exception $exception){
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