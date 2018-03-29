<?php
namespace controllers;

/**
 * Class IndexController
 * @package controllers
 * @controller
 */
class IndexController extends \SDF\Controllers\BaseController{

    /**
     * @Route ('/index')
     */
    public function index(){

        $publishInfo = [
            'publish'=> ['nickname'=>'丢丢君'],
            'publish_time' => '一天前',
            'content' => '本人在两口丢失一张公交卡，联系电话18523431231',
            'attachment' => ['http://img.taopic.com/uploads/allimg/120727/201995-120HG1030762.jpg'],
            'address' => '枇杷山正街84号',
            'type' => '交通工具',
            'looks' => 20,
        ];

        $data = [
            'swipe' => [
                ['name'=>'1','pic'=>'http://img.taopic.com/uploads/allimg/120727/201995-120HG1030762.jpg'],
                ['name'=>'2','pic'=>'http://img.zcool.cn/community/0142135541fe180000019ae9b8cf86.jpg@1280w_1l_2o_100sh.png'],
                ['name'=>'3','pic'=>'http://img.zcool.cn/community/018d4e554967920000019ae9df1533.jpg@900w_1l_2o_100sh.jpg'],
            ]
        ];
        $this->toJson($data);
    }


    public function info(){

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