<?php
namespace SDF\IOC;

class SummerFactory {

    private static $summerInstance = null;

    /**
     * 获取summer
     * @param $configFile
     * @return null|Summer
     */
    public static function getContext4File($configFile){
        if(self::$summerInstance == null){
            self::$summerInstance = new Summer();
        }

        self::$summerInstance->loadConfigFile($configFile);

        return self::$summerInstance;
    }

    public static function getContext4Path($configPath){
        if(self::$summerInstance == null){
            self::$summerInstance = new Summer();
        }

        self::$summerInstance->loadConfigPath($configPath);

        return self::$summerInstance;
    }

    /**
     * @param $cDir 自动识别类
     * @return null|Summer
     */
    public static function getAutoContext($cDir){
        if(self::$summerInstance == null){
            self::$summerInstance = new Summer();
        }
        self::$summerInstance->autoGenerateConfig($cDir);
        return self::$summerInstance;
    }


}