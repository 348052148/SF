<?php
namespace Domain\models\user;

use Domain\models\post\Post;

class PostLosser extends User {

    public function __construct($uid)
    {
        parent::__construct($uid);
    }

    public function backGoods(Post $post){
        $post->status = 1;
    }
}