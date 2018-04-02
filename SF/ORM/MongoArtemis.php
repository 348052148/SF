<?php
namespace SF\ORM;

use SF\DB\MongoDatabaseSource;

class MongoArtemis implements IArtemis{

    private $where=[];

    private $sort=[];

    private $fields = [];

    private $group = [];

    private $connection = null;

    private $offset = 0;

    private $limit = 0;

    private $current;

    public function __construct($table,$database)
    {
        $this->table = $table;
        $this->database = $database;
        $this->connection =  MongoDatabaseSource::getConnection($this->database,$this->table);
    }

    public function findAndUpdate($filter,$data){
        $this->current = $this->connection->findAndModify($filter,['$set'=>$data],[]);
    }
    // 条件
    public function where($where){
        $this->where = $where;
    }


    public function orderBy($field, $asc)
    {
        $this->sort[$field] = $asc;
    }

    public function update($newobj)
    {
        return $this->connection->update($this->where,['$set'=>$newobj]);
    }

    public function delete()
    {
        return $this->connection->delete($this->where);
    }

    public function insert($data)
    {
        return $this->connection->insert($data);
    }

    // 分组
    public function group($group){
        $this->group = $group;
    }
    // 获取所有
    public function get($fields=[]){
        $this->fields = $fields;
        $this->current = $this->connection->find($this->where,$this->fields,$this->offset,$this->limit,$this->sort);
        if(empty($this->current)){
            return false;
        }
        return true;
    }

    // 获取第一条
    public function first($fields=[]){
        $this->fields = $fields;
        $this->current = $this->connection->findOne($this->where,$this->fields,$this->sort);
        if(empty($this->current)){
            return false;
        }
        return true;
    }
    // 获取最后一条
    public function last($fields=[]){
        $this->fields = $fields;
        $this->current = $this->connection->findOne($this->where,$this->fields,$this->sort);
        if(empty($this->current)){
            return false;
        }
        return true;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
    }

    // 转化数组
    public function toArray(){
        if(empty($this->current)){
            return false;
        }
        if(is_array($this->current)){
            $list = [];
            foreach ($this->current as $v){
                $list[] = $this->object_array($this->current);
            }
            return $list;
        }
       return $this->object_array($this->current);
    }
    // 转化json
    public function toJson(){

    }

    function object_array($array)
    {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (!is_a($value, 'MongoDB\BSON\ObjectId')) {
                    $array[$key] = $this->object_array($value);
                }
            }
        }
        return $array;
    }
}