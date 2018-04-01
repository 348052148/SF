<?php
namespace SF\ORM;

class MongoArtemis implements IArtemis{

    private $where=[];

    private $sort=[];

    public function save(){

    }
    // 条件
    public function where($where){
        $this->where = $where;
    }
    // 获取第一条
    public function first(){
        var_dump($this->where);
    }
    // 获取最后一条
    public function last(){

    }
    // 排序
    public function order(){

    }
    // 分组
    public function group(){

    }
    // 获取所有
    public function get(){

    }
    // 转化数组
    public function toArray(){

    }
    // 转化json
    public function toJson(){

    }
}