<?php
/**
 * User: yuanji
 * Date: 2014-10-24 11:16:44
 */


namespace SF\Cache;
use SF\Core\Config;
class Redis extends \Redis{
    protected static $instances=array();//redis连接对象
    protected static $_config =array(
        'name'=>'default',
        'server' =>  \redisConf::REDIS_SERVER ,//服务器地址
        'port'   =>  \redisConf::REDIS_PORT ,//端口地址
        'timeout'=>  \redisConf::REDIS_TIMEOUT,//超时时间
        'dbindex'=>  \redisConf::REDIS_DBINDEX ,//数据库名称
        'username'=> \redisConf::REDIS_USERNAME ,//数据库用户名
        'password'=> \redisConf::REDIS_PASSWORD ,//数据库密码
        'pconnect'=> \redisConf::REDIS_PCONNECT,//是否启用长连接
        'auth_pass'=>\redisConf::REDIS_AUTH_PASS,
        'option' => array('connect' => true),// 参数
    );
    protected static $configs = array();//所有使用过的连接配置

    /**
     * 创建redis连接对象
     * Redis constructor.
     * @param null $dbindex
     * @param string $config_name
     */
    public function __construct($dbindex=NULL,$config_name='default') {
        parent::__construct();
        self::$configs[$config_name] = $redis_config = Config::getField('redis',$config_name,self::$_config);
        if($redis_config['pconnect']) { //是否使用长连接
            $this->pconnect($redis_config['server'], $redis_config['port'], $redis_config['timeout'], $config_name);
            $this->auth($redis_config['auth_pass']);
        } else {
            $this->connect($redis_config['server'], $redis_config['port'], $redis_config['timeout']);
            $this->auth($redis_config['auth_pass']);
        }
        $this->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        //self::$instances[$config_name] = $redis;
        $this->select($dbindex===NULL?intval(@$redis_config['dbindex']):$dbindex);
        self::$instances[$config_name] = $this;
    }

    /**
     * 手动关闭链接
     * @param array $names
     * @return bool
     */
    public static function closeInstance(array $names = array()){
        if (empty(self::$instances)) {
            return true;
        }
        if (empty($names)) {
            foreach (self::$instances as $name => $redis) {
                if (self::$configs[$name]['pconnect']) {
                    continue;
                }
                $redis->close();
                //unset(self::$configs[$name]);
            }
        } else {
            foreach ($names as $name) {
                if (isset(self::$instances[$name])) {
                    if (self::$configs[$name]['pconnect']) {#长连接不关闭
                        continue;
                    }
                    self::$instances[$name]->close();
                    //unset(self::$configs[$name]);
                }
            }
        }
        return true;
    }
}
