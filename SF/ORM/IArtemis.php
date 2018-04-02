<?php
namespace SF\ORM;

interface IArtemis {
    // 保存
    public function findAndUpdate($filter,$data);
    // 条件
    public function where($where);
    // 获取第一条
    public function first($fields=[]);
     // 获取最后一条
    public function last($fields=[]);
    // 分组
    public function group($group);
    // 获取所有
    public function get($fields=[]);

    public function offset($offset);

    public function limit($limit);

    public function update($newobj);

    public function insert($data);

    public function delete();

    public function orderBy($field,$asc);
    // 转化数组
    public function toArray();
    // 转化json
    public function toJson();

}