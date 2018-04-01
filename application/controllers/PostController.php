<?php
namespace controllers;

/**
 * Class IndexController
 * @package controllers
 * @Controller ('posts')
 */
class PostController extends \SDF\Controllers\BaseController{
    public function __construct()
    {
        $publishInfo = [
            'publish'=> ['nickname'=>'丢丢君'],
            'publish_time' => '一天前',
            'content' => '本人在两口丢失一张公交卡，联系电话18523431231',
            'attachment' => ['http://img.taopic.com/uploads/allimg/120727/201995-120HG1030762.jpg'],
            'address' => '枇杷山正街84号',
            'type' => '交通工具',
            'looks' => 20,
        ];
    }

    // 列表

    /**
     * @Route (value='/posts')
     */
    public function posts(){
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

    /**
     * 新建一个 posts
     * @Route (value='/posts',method='POST')
     */
    public function addPost(){
        $data = [
            'publish_uid'=> '123',
            'used_uid'=> '123',
            'publish_type'=> 1,//1 失物招领 2 寻物启事
            'publish_time' => time(),
            'content' => $_REQUEST['content'],
            'attachment' => [],
            'address' => $_REQUEST['address'],
            'location' => [
                'lat' => '','lng'=>''
            ],
            'staus' => 0, // 0 新创建 1 已认领 (已归还） 2 (已确认) 3
            'amount' => 0, // 悬赏金额
            'entity_class' => '交通工具',
            'looks' => 0,
            'tags' => ['丢'], // 设置标签
        ];
        $this->toJson(['msg'=>'发布成功']);
    }

    /**
     * 删除一个post
     * @Route (value='/posts',method='DELETE')
     */
    public function delPost($res,$req,$id){

    }



    /**
     * @Route (value='/posts/{id}',method='GET')
     */
    public function postsByid($req,$res,$id){
        $data = [
            'publish'=> ['nickname'=>'丢丢君'],
//            'publish_time' => '一天前',
            'publish_time' => date('Y-m-d H:i:s',time()),
            'content' => '早上在渝北区线外城市花园711吃面包，不慎遗落心爱的马克杯，望有看到者联系。电话18523922708',
            'attachment' => ['http://img.taopic.com/uploads/allimg/120727/201995-120HG1030762.jpg'],
            'address' => '枇杷山正街84号',
            'type' => '交通工具',
            'looks' => 20,
            'amount' => '0.00',
            'tags'=>['丢']
        ];
        $this->toJson($data);
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