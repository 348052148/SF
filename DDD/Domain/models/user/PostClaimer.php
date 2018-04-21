<?php
namespace Domain\models\user;

class PostClaimer extends User{

    public function claimeGoods($post){
        $post->status = 1;
    }

}