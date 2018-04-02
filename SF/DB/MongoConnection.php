<?php
namespace SF\DB;

class MongoConnection implements IConnection{
    private $driver;

    public function setDriver($driver)
    {
        $this->driver = $driver;
        // TODO: Implement setDriver() method.
    }


    public function delete($filter)
    {
        $this->driver->delete($filter);
    }

    public function find($filter,$projection=[],$skip=0,$limit=1,$sort=[])
    {
        return $this->driver->query($filter,$projection,$limit,$skip,$sort);
    }

    public function findAndModify($filter,$update,$fields=[],$sort=[],$option=['new'=>true,'upsert'=>true])
    {
        $cmd = array(
            'findAndModify' => $this->driver->getCollectionName(),//用于运行此命令的集合，命令详解：https://docs.mongodb.com/manual/reference/command/findAndModify/
            'update' => $update,//修改语句
            'query' => (object)$filter,//筛选条件
            'fields' => $fields,//返回的字段
            'sort' => $sort,//对筛选结果排序
        );

        $cmd = array_merge($cmd,$option);

      return $this->driver->command($cmd);
    }
    public function findOne($filter,$projection=[],$sort=[])
    {
       return $this->driver->query($filter,$projection,1,0,$sort);
    }
    public function insert(&$document)
    {
        return $this->driver->insert($document);
    }
    public function update($filter,$newObj)
    {
        return $this->driver->update($filter,$newObj);
    }
}