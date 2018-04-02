<?php
namespace SF\DB;

/**
 * 提供数据库统一操作 屏蔽持久存储差异
 * Interface IConnection
 * @package SF\DB
 */
interface IConnection {

    public function setDriver($driver);

    public function findOne($filter,$projection=[],$sort=[]);

    public function find($filter,$projection=[],$skip=0,$limit=1,$sort=[]);

    public function update($filter,$newObj);

    public function insert(&$document);

    public function delete($filter);

    public function findAndModify($filter,$update,$fields=[],$option=[]);
}