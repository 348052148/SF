<?php
namespace UI\controllers;

use UI\dtos\RequestDto;

class PostController {

    /**
     * @route ('/posts',method='POST')
     */
    public function addPost(){
        //组装Dto过程
        $PostDto = new RequestDto();
        $PostDto->uid = $_REQUEST['uid']; //发布者
        $PostDto->content = $_REQUEST['content']; //发布内容
        //请求服务


    }
}