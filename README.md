## 简介

 是一个免费开源的，快速、简单的面向对象的 轻量级PHP开发框架 。

## nginx伪静态配置
    location / {
        if ( !-e $request_filename ) {
            #rewrite .*\..* /404.html last;
            rewrite ^(.*)/?$ /index.php?$1 last;
        }
    }
## 设计原理
整个框架基于一个ioc容器构建。通过ioc来实现控制反转和依赖注入。
## 基于注释的解析
### 依赖注入
```
class controller{
 /**
 *  会将orderService 服务注入到 orderService 中
 * @Resource ("orderService")
 **/
 private $orderService;
}
```
### 路由解析
```
class controller{
 /**
 *  GET 访问 http://domain/posts 将会映射到本方法 输出 404
 * @Route ("/posts",method="GET")
 **/
 public function getPostList(){
   return '404'
 }
}
```
## ORM操作
### 对象映射
```
class controller{
 /**
 *  将会获得 User 对象，
 * @Route ("/posts",method="GET")
 **/
 public function getPostList(){
    $user = UserDao::where(['id'=>1])->find();
    return $user->id;
 }
 ```
### 查询构造器
```
class controller{
 /**
 *  使用查询构造器将会获得完整的数据
 * @Route ("/posts",method="GET")
 **/
 public function getPostList(){
    $user = UserDao::where(['id'=>1])->orderBy('id')->first()->toArray();
    return $user['id']
 }
}
```
## 后期
1, 实现 aop
2, 实现非nosql数据库驱动,以及事务
3, 完整的错误处理栈
4, 针对rpc服务化构建
