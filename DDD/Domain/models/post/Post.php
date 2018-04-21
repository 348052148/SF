<?php
namespace Domain\models\post;

class Post {

    public $id;

    public $title;

    public $content;

    public $status;

    public $postpublisher;

    public function __construct($id=null)
    {
        if($id!=null){
            $this->id = $id;
        }
    }

}