<?php
namespace dao;

use SF\ORM\Artemis;

class CategoryDao extends Artemis {

    public function __setOption()
    {
        $this->databses = 'lostApp';
        $this->table = 'category';
    }
}