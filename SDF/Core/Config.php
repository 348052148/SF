<?php
/**
 * User: yuanji
 * Date: 2014年10月21日18:17:57
 * config配置处理
 */

namespace SDF\Core;
use SDF\Common\Dir;

class Config{
    private static $config = array();//配置数据
    private static $files = array();//已加载的配置文件

    /**
     * 加载并初始化配置
     * @param $configPath
     * @return array|mixed
     */
    public static function load($configPath,$alias=null) {
        self::$files = glob($configPath.DS.'*.php');
        if($alias == null) {
            $config = array();
        }else{
            $config = is_array(self::$config[$alias])?self::$config[$alias]:[];
        }
        if (!empty(self::$files)) {
            foreach (self::$files as $file) {
                $tmpConf = include "{$file}";
                if(is_array($tmpConf))
                    $config = array_merge($config,$tmpConf);
            }
        }
        if($alias == null){
            self::$config = $config;
        }else{
            if(is_string($alias))
            self::$config[$alias] = $config;
        }

        return $config;
    }

    /**
     * 加载新的配置文件，如果文件已经加载过。不会重复加载
     * @param array $files
     * @return array|mixed
     */
    public static function loadFiles(array $files) {
        $config = array();
        foreach($files as $filename) {
            self::$files += include "{$filename}";
            $config += include "{$filename}";
        }
        self::$config += $config;
        return $config;
    }

    /**
     * 读取文件
     * @param array $files
     * @param $alias
     * @return array|mixed
     */
    public static function loadFileAsName(array $files,$alias) {
        $config = array();
        foreach($files as $filename) {
            self::$files += include "{$filename}";
            $config += include "{$filename}";
        }
        self::$config[$alias] += $config;
        return $config;
    }

    /**
     * 获取指定配置参数的值
     * @param $key  配置参数名
     * @param null $default     如果在现有配置中没有这个配置项，是用这个值代替
     * @param bool $throw       如果配置项不存在，是否抛出异常，默认不抛出
     * @return mixed|null
     * @throws \Exception
     */
    public static function get($key, $default = null, $throw = false) {
        $result = isset(self::$config[$key]) ? self::$config[$key] : $default;
        if ($throw && empty($result)) {
            throw new \Exception("{key} config empty");
        }
        return $result;
    }

    /**
     * 获取指定字段的值
     * @param $key
     * @param $filed
     * @param null $default
     * @param bool $throw
     * @return null
     * @throws \Exception
     */
    public static function getField($key, $filed, $default = null, $throw = false){
        $result = isset(self::$config[$key][$filed]) ? self::$config[$key][$filed] : $default;
        if ($throw && empty($result)) {
            throw new \Exception("{key} config empty");
        }
        return $result;
    }

    public  static function setConf($alias,$confval){
        if(is_string($alias))
        self::$config[$alias] = $confval;
        return true;
    }

    /**
     * 取得所有配置
     * @return array
     */
    public static function getAllConf() {
        return self::$config;
    }

    /**
     * 获取已加载的配置文件
     * @return array
     */
    public static function getFiles(){
        return self::$files;
    }
}
