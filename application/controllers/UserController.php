<?php
namespace controllers;
use dao\CategoryDao;
use dao\PostDao;


class UserController extends \SF\Controllers\BaseController{

    public function __construct()
    {
        $categoryLst =  CategoryDao::get()->toArray();

        $this->categoryArr = [];
        foreach ($categoryLst as $category){
            $this->categoryArr[$category['_id'].""] = $category['name'];
        }
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
       return $this->toJson($data);
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

//        $data = [
//            'publish_uid'=> $_REQUEST['puid'],
//            'used_uid'=> '',
//            'publish_type'=> $publish_type,//1 失物招领 2 寻物启事
//            'publish_time' => time(),
//            'content' => $_REQUEST['content'],
//            'attachment' => [],
//            'address' => $_REQUEST['address'],
//            'addressDetail' => $_REQUEST['addressDetail'],
//            'location' => [
//                'lat' => '','lng'=>''
//            ],
//            'staus' => 0, // 0 新创建 1 已认领 (已归还） 2 (已确认) 3
//            'amount' => $_REQUEST['amount'], // 悬赏金额
//            'entity_class' => $_REQUEST['entity_class'],
//            'looks' => 0,
//            'tags' => [], // 设置标签
//        ];

        $page = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $uid = $id;
        $limit = 3;
        $publish_type = intval($_REQUEST['publish_type']);
        $list = [];
        $postMode =  PostDao::where(['publish_uid'=>$uid,'publish_type'=>$publish_type])->offset(($page-1)*$limit)->limit($limit)->get();

        if($postMode == false){
            return $this->toJson([],-1,'no data');
        }
        $postLst = $postMode->toArray();
        foreach ($postLst as $post){
            $post['publish_time'] = date('Y-m-d H:i:s',$post['publish_time']);
            $post['entity_class'] = $this->categoryArr[$post['entity_class']];
            $post['id'] = $post['_id']."";
            $list[] = $post;
        }

        return $this->toJson($list);
    }
}