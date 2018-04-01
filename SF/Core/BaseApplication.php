<?php
namespace SF\Core;

use SF\IOC\Summer;
use SF\IOC\SummerFactory;
use SF\Route\MVCRoute;

abstract class BaseApplication implements Application{

    protected $app_path = 'application';

    protected $controller_suffix = 'Controller';

    protected $controller_namespace = 'controllers';

    protected $current_controller = '';

    protected $current_action = '';

    protected $current_parames = [];

    protected $controller_instance;

    protected $parames = [];

    /**
     * @var MVCRoute
     * @Resource(name="SFMVCRoute")
     */
    protected $router;

    public function __construct($appPath = 'application',$namespace='controllers')
    {
        $this->app_path = $appPath;
        $this->controller_namespace = $namespace;
        //加载并合并自定义注入配置
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
            $this->setController($this->router->getClassName());

            $aciton = $this->router->findAction();


            $this->parames = $this->router->getParames();


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
        $this->setControllerInstance($this->router->findInstance());

        $this->doAction();
    }
}