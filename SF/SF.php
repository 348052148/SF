<?php
namespace SF;

use SF\Core\Config;

class Loader {

    static $classPath;

    static $libPath = [];

    public static function load()
    {
        \spl_autoload_register(__CLASS__ . '::autoLoader');
    }

    public static function setLoadPath($path){
        array_push(self::$libPath,$path);
    }

    public static function autoLoader($class)
    {
        if (isset(self::$classPath[$class])) {
            require self::$classPath[$class];
            return true;
        }
        $baseClasspath = \str_replace('\\', DS, $class) . '.php';

        foreach (self::$libPath as $lib) {
            $classpath = $lib . DS . $baseClasspath;
            if (file_exists($classpath)&&\is_file($classpath)) {
                self::$classPath[$class] = $classpath;
                /** @var TYPE_NAME $classpath */
                require "{$classpath}";
                return true;
            }
        }
    }
};

class SMB {
    static $rootPath;
    static $appPath = 'application';

    private static $instances = array();

    public static function getAppPath(){
        return self::$appPath;
    }

    public static function setAppPath($path){
        self::$appPath = $path;
    }

    public static function getRootPath(){
        return \rtrim(self::$rootPath,DS).DS;
    }

    public static function setRootPath($rootPath){
        self::$rootPath = $rootPath;
    }

    public static function getConfigPath(){
        return self::getRootPath() . 'config' . DS . 'default';
    }


    public static function getInstance($className, $params = null)
    {
        $keyName = $className;
        if (!empty($params['_prefix'])) {
            $keyName .= $params['_prefix'];
        }
        if (isset(self::$instances[$keyName])) {
            return self::$instances[$keyName];
        }
        if (!\class_exists($className)) {
            throw new \Exception("no class {$className}",404);
            exit;
        }

        if (empty($params)) {
            self::$instances[$keyName] = new $className();
        } else {
            self::$instances[$keyName] = new $className($params);
        }
        return self::$instances[$keyName];
    }

    /**
     * 自定义的异常处理函数
     * @param $exception
     */
    final public static function exceptionHandler($exception){
        if (!empty($_SERVER['argv'][1])) {
            $msg = "<b>Exception:</b>".$exception->getCode() .':'. $exception->getMessage().' in '.$exception->getFile().' on line '.$exception->getLine()."\n";
            echo $msg;
        }else{
            $error_code = $exception->getCode();
            if($error_code == 404){
                $msg = "<b>Exception:</b>".$exception->getCode() .':'. $exception->getMessage().' in '.$exception->getFile().' on line '.$exception->getLine()."\n";
                exit;
            }
            $msg = "<b>Exception:</b>".$exception->getCode() .':'. $exception->getMessage().' in '.$exception->getFile().' on line '.$exception->getLine()."\n";
            exit;
        }
    }

    public static function run($rootPath){
        self::setRootPath($rootPath);
        //类加载
        Loader::load();
        Loader::setLoadPath($rootPath); // 加载框架路径
        Loader::setLoadPath($rootPath.self::getAppPath()); // 加载app路径
        Loader::setLoadPath($rootPath.'Lib'); // 加载lib路径

        // 加载框架配置文件
        Config::load(self::getConfigPath());//加载配置文件

        //DI 配置
        Config::load(self::getRootPath().'/config/di','DIMap');
        if(Config::get('DI',false)){
            $DILst = Config::get('DI_CONF',[]);
            foreach ($DILst as $di){
                Config::load($di,'DIMap');
            }
        }

        //设置app路径
        $appPath = Config::get('app_path', self::$appPath);
        self::setAppPath($appPath);

        //设置时区
        $timeZone = Config::get('time_zone', 'Asia/Chongqing');
        \date_default_timezone_set($timeZone);

        //重载异常
        $eh = Config::getField('project', 'exception_handler', __CLASS__ . '::exceptionHandler');//获取配置文件中配置的异常处理函数
        \set_exception_handler($eh);

    }


}
define('DS',DIRECTORY_SEPARATOR);
include_once 'SFConf.php';
SMB::run(__DIR__.'/../');