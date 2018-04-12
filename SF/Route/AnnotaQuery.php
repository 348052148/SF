<?php
namespace SF\Route;

class AnnotaQuery implements QueryController{
    private $classLst;
    private $action;
    private $nameSpace = 'controllers';
    private $parames = [];

    private $controller=null;
    /**
     * @var mixed
     * @Resource (name="SFSummer")
     */
    private $summer;

    public function __construct()
    {

    }

    public function findParames(){
        return $this->parames;
    }

    /**
     * 寻找action
     * @param $mapRef
     * @return bool|string
     */
    public function findaAction($mapRef)
    {
        if($this->action!=null && is_string($this->action)){
            return $this->action;
        }

        $pathinfo = $mapRef['pathinfo'];
        $classRef = new \ReflectionClass($this->nameSpace.'\\'.$this->findController($mapRef));

        $mould = $this->getModule($classRef);

        $methodLst = $classRef->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methodLst as $method){

            $ants = $this->summer->parseMethod($method);

            foreach ($ants as $ant){
                if($ant['method'] == 'Route'){

                    $req_method = isset($ant['parame']['method'])?trim($ant['parame']['method']):'GET';

                    if($this->parsePath($pathinfo,$mould.$ant['parame']['value']) && $this->getRequestMethod()==strtoupper($req_method) ){

                        return $method->getName();
                    }
                }
            }

        }
        return false;
    }

    private function getModule(\ReflectionClass $classRef){
        $mould = '';
        $classAnts = $this->summer->parseClass($classRef);

        foreach ($classAnts as $ant){
            if($ant['method'] == 'Controller'){
                $mould = '/'.$ant['parame']['value'];
            }
        }
        return $mould;
    }

    /**
     * 寻找controller
     * @param $mapRef
     * @return int|null|string
     */
    public function findController($mapRef)
    {
        if($this->controller!=null&&is_string($this->controller)){
            return $this->controller;
        }
        if(is_null($this->classLst)){
            $this->classLst = $this->summer->getAllBeans4Method();
        }
        foreach ($this->classLst as $className=> $class) {
            foreach ($class as  $cls) {

                if (isset($cls['method']) && $cls['method'] == 'Route'){

                    $req_method = isset($cls['parame']['method'])?trim($cls['parame']['method']):'GET';
                    // todo 获取模块
                    $module = $this->getModule(new \ReflectionClass($this->nameSpace.'\\'.$className));

                    if($this->parsePath($mapRef['pathinfo'],$module.$cls['parame']['value']) && $this->getRequestMethod()==strtoupper($req_method) ){
                        $this->action = $cls['action'];

                        $this->controller = $className;
                        return $className;
                    }
                }
            }
        }
        return 'IndexController';
    }

    private function getRequestMethod(){
        $requestMethod = 'GET';
        if(isset($_GET['m'])&&in_array(strtoupper($_GET['m']),['GET','PUT','POST','DELETE','PATCH'])){
            $requestMethod = strtoupper($_GET['m']);
        }else{
            $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);
        }
        return $requestMethod;
    }

    public function getInstance($mapRef)
    {
        if($this->controller==null){
            $this->findController($mapRef);
        }

        return $this->summer->get($this->controller);
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
        $lcount = count($Lparttern);

        if($rcount != $lcount){
            return false;
        }

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
//
//    private function methodSelect(){
//        if(is_null($this->classLst)){
//            $this->classLst = $this->summer->getAllBeans4Method();
//        }
//        foreach ($this->classLst as $className=> $class) {
//            foreach ($class as  $cls) {
//                if (isset($cls['method']) && $cls['method'] == 'Route'){
//
//                    $req_method = isset($cls['parame']['method'])?trim($cls['parame']['method']):'GET';
//
//                    if($this->parsePath($this->mapRef['pathinfo'],$cls['parame']['value']) && $this->getRequestMethod()==strtoupper($req_method) ){
//                        $this->action = $cls['action'];
//                        return $className;
//                    }
//                }
//            }
//        }
//        return 'IndexController';
//    }
//
//    private function classSelect(){
//        if(is_null($this->classLst)){
//            $this->classLst = $this->summer->getAllBeans();
//        }
//        foreach ($this->classLst as $className=> $class) {
//            foreach ($class as  $cls) {
//                if (isset($cls['method']) && $cls['method'] == 'Controller')
//                    if (isset($cls['parame']['value'])&&$cls['parame']['value'] == $this->mapRef['class']) {
//
//                        return $className;
//                    }
//            }
//        }
//        return 'IndexController';
//    }
}