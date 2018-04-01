<?php
namespace SF\Route;

class MVCRoute extends Route{

    private $nameSpace;
    private $parames;
    /**
     * @var mixed
     * @Resource (name="SFSummer")
     */
    private $summer;

    private $classLst = null;

    private $action = null;

    public function __construct()
    {
        $this->mapRef = $this->parsePathUrl();
        $this->nameSpace = 'controllers';
        $this->parames = [];
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

    public function getClassName(){
        return $this->methodSelect();
    }

    private function methodSelect(){
        if(is_null($this->classLst)){
            $this->classLst = $this->summer->getAllBeans4Method();
        }
        foreach ($this->classLst as $className=> $class) {
            foreach ($class as  $cls) {
                if (isset($cls['method']) && $cls['method'] == 'Route'){

                    $req_method = isset($cls['parame']['method'])?trim($cls['parame']['method']):'GET';

                    if($this->parsePath($this->mapRef['pathinfo'],$cls['parame']['value']) && $this->getRequestMethod()==strtoupper($req_method) ){

                        return $className;
                    }
                }
            }
        }
        return 'IndexController';
    }

    private function classSelect(){
        if(is_null($this->classLst)){
            $this->classLst = $this->summer->getAllBeans();
        }
        foreach ($this->classLst as $className=> $class) {
            foreach ($class as  $cls) {
                if (isset($cls['method']) && $cls['method'] == 'Controller')
                    if (isset($cls['parame']['value'])&&$cls['parame']['value'] == $this->mapRef['class']) {

                        return $className;
                    }
            }
        }
        return 'IndexController';
    }

    public function getParames(){
        return $this->parames;
    }

    /**
     * 找寻实例
     * @param $classMeta
     */
    public function findInstance(){
        return $this->summer->get($this->getClassName());
    }

    /**
     * 规则找寻
     * @param $pathinfo
     * @return bool|string
     */
    public function findAction(){
        if($this->action!=null && is_string($this->action)){
            return $this->action;
        }

        $pathinfo = $this->mapRef['pathinfo'];
        $classRef = new \ReflectionClass($this->nameSpace.'\\'.$this->getClassName());
        $methodLst = $classRef->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methodLst as $method){

            $ants = $this->summer->parseMethod($method);

            foreach ($ants as $ant){
                if($ant['method'] == 'Route'){

                    $req_method = isset($ant['parame']['method'])?trim($ant['parame']['method']):'GET';

                    if($this->parsePath($pathinfo,$ant['parame']['value']) && $this->getRequestMethod()==strtoupper($req_method) ){

                        return $method->getName();
                    }
                }
            }

        }
        return false;
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
}