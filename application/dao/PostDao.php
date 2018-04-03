<?php
namespace dao;

use SF\ORM\Artemis;

class PostDao extends Artemis {

    public function __setOption()
    {
        $this->databses = 'lostApp';
        $this->table = 'post';
    }
}