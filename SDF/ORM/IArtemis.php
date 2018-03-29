<?php
namespace SDF\ORM;

interface IArtemis {
    public function find($filter = array(), array $projection = [], $flag = true);
    public function findOne(array $filter = [], array $projection = []);
    public function findOneById($_id, $fields = array());
    public function findAndModify(array $query, array $update = array(), array $fields = NULL, array $options = NULL);
    public function distinct($key, array $query = NULL);
    public function remove(array $criteria = array(), array $options = array());
    public function update(array $criteria, array $newobj, array $options = array());
    public function batchUpdate(array $criteria,array $newobj,array $options = array());
    public function batchInsert(array $a, array $options = array());
    public function insert(&$a, array $options = array());
    public function save(&$a);
    public function aggregate(array $pipeline, array $op = array(), array $pipelineOperators = array());
    public function count($filter = [],$flag=false);
    public function MongoID($filter = [],$flag=false);
}