<?php
namespace controllers;
use dao\UserDao;

/**
 * Class IndexController
 * @package controllers
 *
 */
class IndexController extends \SF\Controllers\BaseController{

    /**
     * @Route ('/index')
     */
    public function home(){

        $data = [
            // 导航数据
            'swipe' => [
                ['name'=>'1','pic'=>'http://img.taopic.com/uploads/allimg/120727/201995-120HG1030762.jpg'],
                ['name'=>'2','pic'=>'http://img.zcool.cn/community/0142135541fe180000019ae9b8cf86.jpg@1280w_1l_2o_100sh.png'],
                ['name'=>'3','pic'=>'http://img.zcool.cn/community/018d4e554967920000019ae9df1533.jpg@900w_1l_2o_100sh.jpg'],
            ],
            //
        ];
        $this->toJson($data);
    }

    /**
     * @Route (value="/navs")
     */
    public function nav(){
        $data = [
            'navList' => [
                ['icon'=>'http://img.taopic.com/uploads/allimg/120727/201995-120HG1030762.jpg','title'=>'失物招领'],
                ['icon'=>'http://img.taopic.com/uploads/allimg/120727/201995-120HG1030762.jpg','title'=>'寻物启事'],
                ['icon'=>'http://img.taopic.com/uploads/allimg/120727/201995-120HG1030762.jpg','title'=>'重金悬赏'],
            ]
        ];
        $this->toJson($data);
    }

    /**
     * @Route (value="/test")
     */
    public function test(){
        $userDao =UserDao::where(['enterprise_name'=>'分工合作的粉红色的粉红色的和'])->get()->toArray();
        var_dump($userDao);
//        var_dump($userDao->id);
//        var_dump(UserDao::limit(1)->get()->toArray());
    }

}