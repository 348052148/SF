<?php
namespace Domain\models\user;

use Domain\models\post\Post;

class PostPublisher extends User{

    public function __construct($uid)
    {
        parent::__construct($uid);
    }

    //返回 post实体对象
    public function publish($title,$content){
        $post = new Post();
        $post->title = $title;
        $post->content = $content;

        return $post;
    }
}