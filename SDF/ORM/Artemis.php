<?php
namespace SDF\ORM;
use SDF\Db\MongoDB\MongoCollection;

/**
 * Orm 基础
 * Class Artemis
 * @package SDF\ORM
 */
abstract class Artemis extends Facades implements IArtemis {

    private $instance;

    protected $table;

    protected $database;

    public function __construct()
    {
        $this->setOption();

        $this->instance = new MongoCollection($this->table,$this->database);
    }

    abstract protected function setOption();

    public function find($filter = array(), array $projection = [], $flag = true){
        return $this->instance->find($filter,$projection,$flag);
    }
    public function findOne(array $filter = [], array $projection = []){

        return $this->instance->findOne($filter,$projection);
    }
    public function findOneById($_id, $fields = array()){
        return $this->instance->findOneById($_id,$fields);
    }
    public function findAndModify(array $query, array $update = array(), array $fields = NULL, array $options = NULL){
        return $this->instance->findAndModify($query,$update,$fields,$options);
    }
    public function distinct($key, array $query = NULL){
        return $this->instance->distinct($key,$query);
    }
    public function remove(array $criteria = array(), array $options = array()){
        return $this->instance->remove($criteria,$options);
    }
    public function update(array $criteria, array $newobj, array $options = array()){
        return $this->instance->update($criteria,$newobj,$options);
    }
    public function batchUpdate(array $criteria,array $newobj,array $options = array()){
        return $this->instance->batchUpdate($criteria,$newobj,$options);
    }
    public function batchInsert(array $a, array $options = array()){
        return $this->instance->batchInsert($a,$options);
    }
    public function insert(&$a, array $options = array()){
        return $this->instance->insert($a,$options);
    }
    public function save(&$a){
        return $this->instance->save($a);
    }
    public function aggregate(array $pipeline, array $op = array(), array $pipelineOperators = array()){
        return $this->instance->aggregate($pipeline, $op,$pipelineOperators);
    }
    public function count($filter = [],$flag=false){
        return $this->instance->count($filter,$flag);
    }

    public function MongoID($filter = [],$flag=false){
        return MongoCollection::MongoID($filter,$flag);
    }

    public function format($id){
        return $this->instance->format($id);
    }

}