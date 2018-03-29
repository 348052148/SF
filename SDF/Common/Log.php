<?php
/**
 * author: shenzhe
 * Date: 13-6-17
 * 日志输出类
 */

namespace SDF\Common;
use SDF\Core\Config;
use SDF\SDF;

class Log
{
    const SEPARATOR = "\t";

    public static function info($type, $params = array())
    {
        $t = \date("Ymd");
        $logPath = Config::get('log_path', '');
        if(empty($logPath)) {
            $dir = SDF::getRootPath() . DS . 'log' . DS . $t;
        } else {
            $dir = $logPath . DS . $t;
        }
        Dir::make($dir);
        //$str = \date('Y-m-d H:i:s', Config::get('now_time', time())) . self::SEPARATOR . \implode(self::SEPARATOR, array_map('json_encode', $params));
        $str = \date('Y-m-d H:i:s', Config::get('now_time', time())) . self::SEPARATOR . \implode(self::SEPARATOR, $params);
        $logFile = $dir . \DS . $type . '.log';
        \error_log($str . "\n", 3, $logFile);
    }
}
