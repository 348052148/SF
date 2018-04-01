<?php
/**
 * author: yuanji
 * Date: 2015-12-11 13:00:04
 * 缓存类
 * 缓存类存储的数据都是用于临时存储，不作为持久化数据，意味着允许随时清空缓存数据。请在程序设计中做好相应的处理。
 */

namespace SF\Cache;

use SF\Db\MongoCollection;

class Cache {
    public static $redis;
    public static $mongodb;
    public $driver = 'redis';//驱动
    public $cache_db = 0;

    /**
     * 构造一个缓存类
     * Cache constructor.
     * @param string $adapter
     * @param string $backup
     * @param int $cache_db
     */
    public function __construct($adapter='redis',$backup='mongodb',$cache_db = 0){
        $this->init_cache($adapter,$backup,$cache_db);
    }

    /**
     * 初始化缓存类
     * @param string $adapter
     * @param string $backup
     * @param int $cache_db
     * @return bool
     * @throws \Exception
     */
    private function init_cache($adapter='redis',$backup='mongodb',$cache_db = 0){
        $this->cache_db = $cache_db;
        if(self::is_supported($adapter)){
            $this->driver = strtoupper($adapter);
        }elseif(self::is_supported($backup)){
            $this->driver = strtoupper($backup);
        }else{
            throw new \Exception('找不到可用的缓存驱动',500);
        }
    }

    /**
     * 检测指定的驱动是否支持,并且初始化对应的类
     * @param $adapter
     * @return bool
     */
    public function is_supported($adapter){
        $adapter = strtoupper($adapter);
        if('MONGODB' == $adapter){//检测是否支持MONGODB
            if(class_exists('\MongoCollection')){
                //self::$mongodb = new MongoCollection('cache_'.$this->cache_db);
                return true;
            }
        }
        if('REDIS' == $adapter){//检测是否支持MONGODB
            if(class_exists('\Redis')){
                //self::$redis = new \SF\Db\Redis($this->cache_db);
                return true;
            }
        }
        return false;
    }

    /**
     * 设置数据
     * @param $key
     * @param $val
     * @param $expires_in
     * @return bool
     */
    public function set($key,$val,$expires_in=0){
        if($this->driver == 'MONGODB'){
            if(empty(self::$mongodb))self::$mongodb = new MongoCollection('cache_'.$this->cache_db);
            $ret = self::$mongodb ->save(array(
                '_id'=>$key,
                'val'=>$val,
                'create_time'=>time(),
                'expires_in'=>intval($expires_in)
            ));
            if($ret['ok']){
                return true;
            }
        }
        if($this->driver == 'REDIS'){
            if(empty(self::$redis))self::$redis = new Redis($this->cache_db);
            return self::$redis->set($key,$val,$expires_in);
        }
        return false;
    }

    /**
     * 获取数据
     * @param $key
     * @return bool|null
     */
    public function get($key){
        if($this->driver == 'MONGODB'){
            if(empty(self::$mongodb))self::$mongodb = new MongoCollection('cache_'.$this->cache_db);
            $data = self::$mongodb ->findOne(array('_id'=>$key));
            if(empty($data)){
                return NULL;
            }
            if( isset($data['expires_in']) and $data['expires_in'] > 0 and $data['create_time'] + $data['expires_in'] < time() ){
                self::$mongodb ->remove(array('_id'=>$key));
                return NULL;
            }
            return $data['val'];
        }
        if($this->driver == 'REDIS'){
            if(empty(self::$redis))self::$redis = new Redis($this->cache_db);
            return self::$redis->get($key);
        }
        return false;
    }

    /**
     * 删除
     * @param $key
     * @return bool|null
     */
    public function delete($key){
        if($this->driver == 'MONGODB'){
            if(empty(self::$mongodb))self::$mongodb = new MongoCollection('cache_'.$this->cache_db);
            $ret = self::$mongodb ->remove(array('_id'=>$key));
            if($ret['ok']){
                return $ret['n'];
            }
        }
        if($this->driver == 'REDIS'){
            if(empty(self::$redis))self::$redis = new Redis($this->cache_db);
            return self::$redis->delete($key);
        }
        return false;
    }

    /**
     * 对存储在指定key的数值执行原子的加N操作
     * @param string $key
     * @param int $num
     * @param int $expires_in
     * @return bool|int
     */
    public function inc($key,$num = 1,$expires_in = 0){
        if($this->driver == 'MONGODB'){
            if(empty(self::$mongodb))self::$mongodb = new MongoCollection('cache_'.$this->cache_db);
            self::$mongodb ->update(array('_id'=>$key),array(
                '$inc' => array(
                    'val'=>$num,
                ),
                '$set' => array(
                    'create_time'=>time(),
                    'expires_in'=>intval($expires_in)
                )
            ),array(
                'upsert' => true,//如果没有文档与之匹配，创建一个新文档。
            ));
            return $this->get($key);
        }
        if($this->driver == 'REDIS'){
            if(empty(self::$redis))self::$redis = new Redis($this->cache_db);
            $val = self::$redis->incrBy($key,$num);
            self::$redis->setTimeout($key,$expires_in);
            return $val;
        }
        return false;
    }

    /**
     * 清空缓存
     */
    public function clear(){
        if($this->driver == 'MONGODB'){
            if(empty(self::$mongodb))self::$mongodb = new MongoCollection('cache_'.$this->cache_db);
            $ret = self::$mongodb ->drop();
            if($ret['ok']){
                return @$ret['n'];
            }
        }
        if($this->driver == 'REDIS'){
            if(empty(self::$redis))self::$redis = new Redis($this->cache_db);
            return self::$redis->flushDB();
        }
        return false;
    }
}
