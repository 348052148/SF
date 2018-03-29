<?php
/**
 * Ioc DI 容器
 */
namespace SDF\IOC;

class Summer {
    private $container;
    private $config;
    private $annotationParse;
    public function __construct($autoDir=[],$config = [])
    {
        $this->config = $this->autoDIConfig($autoDir);
        $this->container = new Container();
        $this->config = array_merge($this->config,[]);
        $this->annotationParse = new AnnotationParse($this->config);

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

        //注入当前类 名称
        if($classRef->hasProperty('className')){
            $propertyRef = $classRef->getProperty('className');
            $propertyRef->setAccessible(true);
            $propertyRef->setValue($instance,$classMeta['class']);
        }

        //属性注入
        if(isset($classMeta['propertys']))
        foreach ($classMeta['propertys'] as $propertyName=>$propertyInfo){

            //获取类的属性。无则跳过
            if(!$classRef->hasProperty($propertyName)) continue;

            $propertyRef = $classRef->getProperty($propertyName);

            $i = $this->get($propertyInfo['ref']);

            $propertyRef->setAccessible(true);

            $propertyRef->setValue($instance,$i);
        }
        $this->container->bind($alias,$instance);

        // 再次处理具有aop实例
        $pinfo = $this->annotationParse->findClass($classMeta['class']);
        return new AopProxy($instance,$pinfo);
    }

    /**
     * 不兼容目录套目录结构
     * @param $di_dirs
     * @return array
     */
    private function autoDIConfig($di_dirs){
        $config = [];

        foreach ($di_dirs as $di_dir){
            if(is_dir($di_dir)){
                $handler = opendir($di_dir);
                while( ($filename = readdir($handler)) !== false )
                {
                    if(is_file($di_dir.DIRECTORY_SEPARATOR.$filename)){
                        $DS ="\\";

                        $namespace = implode($DS,array_slice(explode(DIRECTORY_SEPARATOR,$di_dir),1));

                        //略过linux目录的名字为'.'和‘..'的文件
                        if($filename != "." && $filename != "..")
                        {
                            $name = explode('.',$filename)[0];
                            //输出文件名

                            $config[ucfirst($name)] = ['class'=>$namespace.$DS.$name];
                        }
                    }

                }
            }
        }

        return $config;
    }

    public function get($class){
        $instance = $this->container->make($class);
        if($instance){
            return $instance;
        }else{
            return $this->instance($class);
        }
    }

}