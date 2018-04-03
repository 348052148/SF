<?php
namespace controllers;

use dao\UserDao;

class RegisterController extends \SF\Controllers\BaseController{

    /**
     * 获取sid
     * @Route ('/code/{sid}')
     */
    public function getCode()
    {

    }

    /**
     * @Route ('/register',method="POST")
     */
    public function doRegister(){

        $phone = empty($_REQUEST['phone'])?false:$_REQUEST['phone'];
        $vcode = empty($_REQUEST['vcode'])?false:$_REQUEST['vcode'];
        $passwd = empty( $_REQUEST['passwd'])?false:$_REQUEST['passwd'];

        if(!$phone || !$passwd){
            return $this->toJson([],-1,'电话和密码不能为空');
        }

        if(empty($vcode)){
            return $this->toJson([],-1,'验证码错误');
        }

        $data = [
            'nickname' => '丢丢君',
            'tel' => $phone,
            'password' => md5(trim($passwd)),
        ];

        if(UserDao::insert($data)){

        }
        return $this->toJson([]);
    }
}