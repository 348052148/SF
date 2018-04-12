<?php
/**
 * Ioc DI 容器
 */
namespace SF\IOC;

class Summer {
    private $container; //实例容器
    private $config;  // beans  配置
    private $configPath; //默认配置文件目录


    public function __construct($configPath = '')
    {

        $this->container = new Container();
        $this->configPath = empty($configPath)?'runtime'.DIRECTORY_SEPARATOR:$configPath;
        $this->config = $this->loadConfig($this->configPath);
    }

    public function loadConfigArray($config){
        $this->config = $config;
            $this->autoGenerateConfig();
    }
    /**
     * 读取文件配置
     * @param $cfile
     */
    public function loadConfigFile($cfile){

        if(is_file($cfile)&&file_exists($cfile)){
            $this->config += include "{$cfile}";
        }

    }

    /**
     * 从目录中读取配置
     * @param $cpath
     */
    public function loadConfigPath($cpath){
        if(is_dir($cpath)){
            $this->config = $this->loadConfig($cpath);
        }

    }

    /**
     * 自动生成所有目录下的beans 并生成配置文件
     * @param array $cDirs
     */
    public function autoGenerateConfig($cDirs=[]){
        $dirs = array_merge([
            ['DIR'=>'application/dao','NAMESPACE'=>'dao'],
            ['DIR'=>'application/services','NAMESPACE'=>'services'],
            ['DIR'=>'application/controllers','NAMESPACE'=>'controllers']
        ],$cDirs);
        $this->loadConfigFile($this->generateConfig($dirs));
    }

    /**
     * 读取目录下的配置文件内容
     * @param $configPath
     * @return array
     */
    private function loadConfig($configPath){
        $files = glob($configPath.DS.'*.php');
        $config = array();
        if (!empty($files)) {
            foreach ($files as $file) {
                $tmpConf = include "{$file}";
                if(is_array($tmpConf))
                    $config = array_merge($config,$tmpConf);
            }
        }

        return $config;
    }

    /**
     * 遍历一个目录下的配置 遵循psr4规范。
     * @param $dirs
     * @param string $namspace
     * @return array
     */
    public function generateConfigAll($dir,$namspace='SF',$sign=''){
        if(is_dir($dir)){
            $handler = opendir($dir);
            while( ($filename = readdir($handler)) !== false )
            {
                if ($filename == "." || $filename == "..") {
                    continue;
                }

                //输出文件名
                $DS ="\\";


                if(is_dir($dir.DIRECTORY_SEPARATOR.$filename)){
                    if(in_array($filename,['Lib','IOC-OLD','Manager','SE'])){
                        continue;
                    }
                    $this->generateConfigAll($dir.DIRECTORY_SEPARATOR.$filename,$namspace.$DS.$filename,$namspace);
                }
                if(is_file($dir.DIRECTORY_SEPARATOR.$filename)){

                    //略过linux目录的名字为'.'和‘..'的文件
                    if($filename != "." && $filename != "..")
                    {
                        if(preg_match('/.php$/',$filename) == true) {

                            $name = explode('.', $filename)[0];


                            $namespace = $namspace;//implode($DS,array_slice(explode(DIRECTORY_SEPARATOR,$dir),1));

                            $this->config[$sign.$name] = ['class' => $namespace . $DS . $name];
                        }
                    }

                }

            }
        }

        return $this->config;
    }

    /**
     * 生成配置文件
     */
    private function generateConfig($dirs){

        if(!file_exists($this->configPath."/beans.php")){
            foreach ($dirs as $dir){
                $sign = isset($dir['SIGN'])?$dir['SIGN'] : '';
                $this->generateConfigAll($dir['DIR'],$dir['NAMESPACE'],$sign);
            }
            file_put_contents($this->configPath."/beans.php", "<?php \n return ".var_export($this->config,true).";");
        }



        return $this->configPath."/beans.php";
    }

    /**
     * 遍历所有beans的注释
     * @return array
     */
    public function getAllBeans(){
        $annoList = array();
        foreach ($this->config as $bid => $classMeta){
            if(class_exists($classMeta['class'])){
                $clsRef = new \ReflectionClass($classMeta['class']);
                $classAnno =  $this->parseClass($clsRef);
                if(!empty($classAnno)){
                    $annoList[$bid]=$classAnno;
                }

            }

        }
        return $annoList;
    }

    /**
     * 遍历所有beans方法注释
     */
    public function getAllBeans4Method(){
        $annoList = [];
        // 类循环
        foreach ($this->config as $bid => $classMeta){
            // 类应用
            $clsRef = new \ReflectionClass($classMeta['class']);
            $methodsRef = $clsRef->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($methodsRef as $methodRef){
                $classAnno = $this->parseMethod($methodRef);
                if(!empty($classAnno)){
                    foreach ($classAnno as $clsAnno) {
                        //设置action
                        $clsAnno['action'] = $methodRef->getName();
                        $annoList[$bid][] = $clsAnno;
                    }
                }
            }
        }
        return $annoList;
    }

    // 解析文档的注释
    private function parseAnnotation($annotaDoc){
        $annotaDocLst = explode("\n",$annotaDoc);
        $annotaLst = [];
        foreach ($annotaDocLst as $aDoc){
            $anno  = [];

            //有参方式
            if(preg_match('/\S\s@(\w+)\s?\((.+)\)/',$aDoc,$match)){
                if(count($match)>2) {
                    $anno['method'] = $match[1];
                    $prames = explode(',', $match[2]);
                    foreach ($prames as $p) {

                        if(preg_match('/(\w+)=["|\'](.+)["|\']/', $p, $pa)){
                            if(count($pa)!=3){
                                continue;
                            }
                            @$anno['parame'][$pa[1]] = $pa[2];
                        }
                        // 无参情况
                        elseif(preg_match('/["|\'](.+)["|\']/', $p, $pa)){
                            @$anno['parame']['value'] = $pa[1];
                        }
                    }
                    array_push($annotaLst, $anno);
                }
            }
            //无参方式
            if(preg_match('/\S\s@(\w+)\s?$/',$aDoc,$match)){
                $anno['method'] = $match[1];
                $anno['parame'] = [];
                array_push($annotaLst, $anno);
            }

        }
        return $annotaLst;
    }

    //更加别名获取类实例
    private function instance($alias){
        if(!isset($this->config[$alias]) || !is_array($this->config[$alias])){
            return false;
        }
        $classMeta = $this->config[$alias];
        $classRef = new \ReflectionClass($classMeta['class']);

        //检查是否可以实例化
        if(!$classRef->isInstantiable()){
            return false;
        }

        // 先处理依赖
        if(isset($classMeta['depend'])){
            foreach ($classMeta['depend']['include'] as $inc){
                if(is_file($inc)){
                    require_once $inc;
                }

            }
        }

        // 构造函数注入
        $args = [];

        if(isset($classMeta['construct']))
        foreach ($classMeta['construct'] as $parame){
            $argv = $this->get($parame['ref']);
            array_push($args,$argv);
        }

        $instance = $classRef->newInstanceArgs($args);

        //todo 方法注入
        $this->methodDi($classRef,$instance);

        //todo 属性注入
        $this->propertyDi($classRef,$instance);

        $this->container->bind($alias,$instance);
        return new Proxy($classRef,$instance);
    }

    /**
     * 类注入
     * @param \ReflectionClass $classRef
     * @param $instance
     */
    private function classDi(\ReflectionClass $classRef,&$instance){
        $annoLst = $this->parseAnnotation($classRef->getDocComment());

        foreach ($annoLst as $a){

            if($a['method'] == 'Plugs'){
                if(isset($a['parame']['value'])){
                    $ref = $a['parame']['value'];
                    if($classRef->hasMethod('pushPlugs')){
                        call_user_func_array([$instance,'pushPlugs'],$ref);
                    }
                }
            }

        }
    }

    /**
     * 方法注入
     * @param \ReflectionClass $classRef
     * @param $instance
     */
    private function methodDi(\ReflectionClass $classRef,&$instance){

        $methods = $classRef->getMethods();

        foreach ($methods as $method){

            $annoLst = $this->parseAnnotation($method->getDocComment());

            foreach ($annoLst as $a){

            }
        }


    }

    /**
     * 属性注入
     * @param \ReflectionClass $classRef
     * @param $instance
     */
    private function propertyDi(\ReflectionClass $classRef,&$instance){
        //todo 属性注入
        $propertyLst = $classRef->getProperties();

        foreach ($propertyLst as $property){
            $annoLst = $this->parseAnnotation($property->getDocComment());
            $ref = false;
            foreach ($annoLst as $a){
                if($a['method'] == 'Resource'){
                    if(isset($a['parame']['name'])){
                        $ref = $a['parame']['name'];
                        break;
                    }
                }
                if($a['method'] == 'Qualifier'){
                    if(isset($a['parame']['value'])){
                        $ref = $a['parame']['value'];
                        break;
                    }
                }
                if($a['method'] == 'Autowired'){
                    $ref = $property->getName();
                    break;
                }
            }
            //todo 如果没有找到则跳过属性的依赖注入
            if(!$ref){
                continue;
            }
            // 获取 beans
            $i = $this->get($ref);

            $property->setAccessible(true);

            $property->setValue($instance,$i);
        }
    }

    /**
     * 解析类获取参数
     * @param \ReflectionClass $classRef
     * @return array
     */
    public function parseClass(\ReflectionClass $classRef){
        return $this->parseAnnotation($classRef->getDocComment());
    }

    /**
     * 解析方法获取参数
     * @param \ReflectionMethod $methodRef
     * @return array
     */
    public function parseMethod(\ReflectionMethod $methodRef){
        return $this->parseAnnotation($methodRef->getDocComment());
    }

    /**
     * 解析属性获取参数
     * @param \ReflectionProperty $propertyRef
     * @return array
     */
    public function parsePropertie(\ReflectionProperty $propertyRef){
        return $this->parseAnnotation($propertyRef->getDocComment());
    }

    /**
     * 获取实例
     * @param $class
     * @return bool|object
     */
    public function get($class,$scope=""){
        $instance = $this->container->make($scope.$class);
        if($instance){
            return $instance;
        }else{
            return $this->instance($scope.$class);
        }
    }

}