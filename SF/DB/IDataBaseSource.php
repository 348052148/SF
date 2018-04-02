<?php
namespace SF\DB;

/**
 * 使用GOF 桥接模式（抽象与行为分离） 各种数据源 是为抽象  获取的操作一致 是为行为一致
 * Interface IDataBaseSource
 * @package SF\DB
 */
interface IDataBaseSource {

    /**
     * 获取驱动
     * @return mixed
     */
    public static function getDirver();

    public static function getConnection($database,$table);



}