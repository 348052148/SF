<?php
namespace SF\Controllers;
/**
 * 控制器增强插件
 * Class PlugFactory
 * @package SF\Controllers
 */
class PlugFactory {

    public static function getPlugs(){

        return [
            JsonPlugs::class
        ];
    }
}