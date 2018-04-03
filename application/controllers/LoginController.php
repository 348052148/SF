<?php
namespace controllers;

use dao\UserDao;

class LoginController extends \SF\Controllers\BaseController{

    /**
     * 登录
     * @Route ("/login",method='POST')
     */
    public function login(){
        $phone = empty($_REQUEST['phone'])?false:$_REQUEST['phone'];

        $passwd = empty($_REQUEST['passwd'])?false:$_REQUEST['phone'];

        if(!$phone || !$passwd){
            return $this->toJson([],-1,'电话和密码不能为空');
        }

        $user = UserDao::where(['tel'=>$phone])->find();


        if(!$user){
            return $this->toJson([],-1,'此用户不存在');
        }

        if(md5($passwd) == $user->password){

            return $this->toJson([]);
        }
        return $this->toJson([],-1,'密码错误');
    }

}