<?php
namespace controllers;

/**
 * Class IndexController
 * @package controllers
 * @Controller ('users')
 */
class UserController extends \SDF\Controllers\BaseController{

    public function __construct()
    {

    }

    /**
     * 获取一个用户信息
     * @Route (value='/users/{id}',method='GET')
     */
    public function getUser(){
        $data = [
            'id' => '123',
            'nickname' => '丢丢君',
            'tel' => '18523922709',
            'password' => '123456',
            'balance' => 0,
            'register_time' => time(), // 注册时间
            'regitser_addr' => '', /// 注册地址
            'energy' => 0, // 能量值
        ];
        $this->toJson($data);
    }

    /**
     * 新建一个用户
     * @Route (value='/users',method='POST')
     */
    public function addUser(){
        $data = [
            'id' => '123',
            'nickname' => '丢丢君',
            'tel' => '18523922709',
            'password' => '123456',
        ];
    }

    /**
     * 更新用户信息
     * @Route (value='/users',method='PUT')
     */
    public function updateUser(){
        $data = [
            'id' => '123',
            'nickname' => '丢丢君',
            'tel' => '18523922709',
            'password' => '123456',
        ];
    }

    /**
     * 删除一个用户
     * @Route (value='/users',method='DELETE')
     */
    public function delUser(){
        $data = [
            'id' => '123',
            'nickname' => '丢丢君',
            'tel' => '18523922709',
            'password' => '123456',
        ];
    }

    /**
     * 获取某个用户的posts列表
     * @Route (value='/users/{id}/posts',method='GET')
     */
    public function getPostByUser($req,$res,$id){

        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $limit = 3;
        $list = [];
        for($i=0;$i < 3;$i++ ) {
            array_push($list,[
                'publish'=> ['nickname'=>'丢丢君'],
                'publish_time' => '一天前',
                'content' => '本人在两口丢失一张公交卡，联系电话18523431231',
                'attachment' => ['http://img.taopic.com/uploads/allimg/120727/201995-120HG1030762.jpg'],
                'address' => '枇杷山正街84号',
                'type' => '交通工具',
                'looks' => 20,
            ]);
        }

        $this->toJson($list);
    }

    private function toJson($data,$code=0,$msg='SUCCESS'){
        header('Access-Control-Allow-Origin:*');
        echo json_encode([
            'code' => $code,
            'message' => $msg,
            'data' => $data
        ]);exit;
    }
}