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

    public function save(){
        
        return $this;
    }
    // 条件
    public function where($where){
        $this->where = $where;
        return $this;
    }
    // 获取第一条
    public function first($fields=[]){
        $this->fields = $fields;
        $this->current = $this->connection->findOne($this->where,$this->fields,$this->sort);
        return $this;
    }
    // 获取最后一条
    public function last($fields=[]){
        $this->fields = $fields;
        $this->current = $this->connection->findOne($this->where,$this->fields,$this->sort);
        return $this;
    }

    public function orderBy($field, $asc)
    {
        $this->sort[$field] = $asc;
        return $this;
    }

    // 分组
    public function group($group){
        $this->group = $group;
        return $this;
    }
    // 获取所有
    public function get($fields=[]){
        $this->fields = $fields;
        $this->current = $this->connection->find($this->where,$this->fields,$this->offset,$this->limit,$this->sort);
        return $this;
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
       return $this->current->toArray();
    }
    // 转化json
    public function toJson(){

    }
}