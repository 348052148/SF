<?php
namespace SF\DB\Driver\MongoDB;

use MongoDB\BSON\ObjectId;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Command;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Query;
use MongoDB\Driver\ReadPreference;
use MongoDB\Driver\WriteConcern;

class MongoDriver {

    private static $Manager;

    private $databaseName;

    private $collectionName;

    public function __construct($collectionName = '', $databaseName = 'ismbao')
    {

        $this->databaseName = $databaseName;

        if (empty($collectionName)) {
            $collectionName = basename(str_replace('\\', '/', get_class($this)));
        }
        $this->collectionName = $collectionName;

        if (!isset(self::$Manager)) {
            self::$Manager = new Manager($this->mongo_config['dsn'],$this->mongo_config['option']);
        }
        if(!empty($this->mongo_config['option']['ReadPreference'])){
            self::$Manager = self::$Manager->selectServer(new ReadPreference($this->mongo_config['option']['ReadPreference']));
        }
        $this->readPreference = self::$Manager->getReadPreference();
    }

    /**
     * 查询方法
     * @param $filter
     * @param array $queryOptions ['projection',]
     * @param ReadPreference|NULL $readPreference
     * @return \MongoDB\Driver\Cursor
     */
    public function query($filter, array $project = [],int $limit = 0,int $skip = 0,array $sort = [],ReadPreference $readPreference = NULL){
        $queryOptions = [
            'projection' => $project,
            'limit' => $limit,
            'skip' => $skip,
            'sort' => $sort
        ];

        $query = new Query($filter, $queryOptions);
        if(empty($readPreference)){
            $readPreference = $this->readPreference;
        }

        $collectionName = $this->collectionName;
        $databaseName = $this->databaseName??'ismbao';
        $cursor = self::$Manager->executeQuery($databaseName . '.' . $collectionName, $query,$readPreference);

        return $cursor;
    }

    /**
     * @return string
     */
    public function getCollectionName(): string
    {
        return $this->collectionName;
    }

    public function command($cmd,ReadPreference $readPreference = NULL){
        if(empty($readPreference)){
            $readPreference = $this->readPreference;
        }
        $this->databaseName = $this->databaseName??'ismbao';
        $command = new Command($cmd);//mongodb各种命令详解：https://docs.mongodb.com/manual/reference/command/nav-crud/
        $cursor = self::$Manager->executeCommand($this->databaseName, $command,$readPreference);

        return $cursor;
    }

    public function insert(&$document, array $options = [],WriteConcern $writeConcern = NULL) {
        if(empty($writeConcern)){
            $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 5000);
        }
        $bulk = new BulkWrite($options);
        $insertId = $bulk->insert($document);
        if(!empty($insertId)) $document['_id'] = $insertId;
        $collectionName = $this->collectionName;
        $databaseName = $this->databaseName??'ismbao';
        $writeResult  = self::$Manager->executeBulkWrite($databaseName.'.'.$collectionName, $bulk, $writeConcern);

        return $insertId;
    }

    public function update($filter,$newObj, array $options = ['multi' => false,'upsert' =>true],WriteConcern $writeConcern = NULL) {
        if(empty($writeConcern)){
            $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 5000);
        }

        $bulk = new BulkWrite($options);

        $updateOptions = $options;

        $bulk->update($filter,$newObj ,$updateOptions);

        $collectionName = $this->collectionName;
        $databaseName = $this->databaseName??'ismbao';
        $writeResult  = self::$Manager->executeBulkWrite($databaseName.'.'.$collectionName, $bulk, $writeConcern);

        return $writeResult;
    }

    public function delete($filter,array $options = ['limit' => true],WriteConcern $writeConcern = NULL) {
        if(empty($writeConcern)){
            $writeConcern = new WriteConcern(WriteConcern::MAJORITY, 5000);
        }
        $bulk = new BulkWrite($options);

        $deleteOptions = $options;

        $bulk->delete($filter,$deleteOptions);

        $collectionName = $this->collectionName;

        $databaseName = $this->databaseName;

        $writeResult  = self::$Manager->executeBulkWrite($databaseName.'.'.$collectionName, $bulk, $writeConcern);
        return $writeResult;
    }
}