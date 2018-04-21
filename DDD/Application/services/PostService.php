<?php
namespace Application\services;

use Domain\models\post\Post;
use Domain\models\user\PostClaimer;
use Domain\models\user\PostLosser;
use Domain\models\user\PostPublisher;
use Domain\repository\PostRepository;
use UI\dtos\RequestDto;
use UI\dtos\ResponseDto;

class  PostService {

    public function __construct()
    {
        $this->postRepository = new PostRepository();
    }

    /**
     * 发布
     * @param RequestDto $post
     * @return ResponseDto
     */
    public function posting(RequestDto $post){
        //发布者
        $postPublisher = new PostPublisher($post->uid);
        //发布返回post实体
        $post = $postPublisher->publish($post->title,$post->content);
        //持久化post实体
        $this->postRepository->save($post);
        //返货response Dto
        $postDto = new ResponseDto();
        $postDto->id = $post->id;

        return $postDto;
    }

    /**
     * 查询详情
     * @param RequestDto $query
     * @return ResponseDto
     */
    public function queryPostDetail(RequestDto $query){
        $query->id;

        $post = $this->postRepository->query($query->id);

        //转化
        $postDto = new ResponseDto();
        $postDto->id = $post->id;
        $postDto->title = $post->title;
        $postDto->content = $post->content;

        return $postDto;
    }

    /**
     * 物品归还
     * @param RequestDto $return
     * @return ResponseDto
     */
    public function postBack(RequestDto $back){
        //订阅者
        $postLosser = new PostLosser($back->uid);

        $post = new Post($back->id);

        $postLosser->backGoods($post);

        $this->postRepository->save($post);

        $postDto = new ResponseDto();
        $postDto->id = $post->id;
        return $postDto;
    }

    /**
     * 物品认领
     * @param RequestDto $claime
     * @return ResponseDto
     */
    public function postClaim(RequestDto $claime){

        $postClaimer = new PostClaimer($claime->uid);

        $post = new Post($claime->id);

        $postClaimer->claimeGoods($post);

        $postDto = new ResponseDto();
        $postDto->id = $post->id;
        return $postDto;
    }

}