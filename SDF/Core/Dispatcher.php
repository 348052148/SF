<?php
namespace SDF\Core;

use SDF\Annotations\AnnotationParse;
use SDF\SMB;

class Dispatcher {

    static $controller = 'IndexController';

    static $action = 'index';

    static $parames = [];

    public static function setController($controller){
        self::$controller = $controller;
    }

    public static function setAction($action){
        self::$action = $action;
    }

    public static function setParames($parames){
        self::$parames = $parames;
    }

    public static function  run(){

        $action =  self::$action;//$pathInfo[1];

        // 考虑是否集成response
        $parames = [
//            'requst','response'
        ];

        $parames = array_merge($parames,self::$parames);

        $instance = self::$controller;

        call_user_func_array(array(&$instance, $action), $parames);
    }
}