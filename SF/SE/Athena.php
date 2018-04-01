<?php
namespace SF\SE;

require_once 'SF/Lib/XunSearch/lib/XS.php';

abstract class Athena {
    static $XS;
    protected $tables;
    public function __construct()
    {
        $this->setOption();

        if(empty(self::$XS)){
            self::$XS = new \XS($this->tables); // 建立 XS 对象，项目名称为：goods
        }
    }
    abstract function setOption();
}