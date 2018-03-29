<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class GoodsDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'goods';
        $this->table = 'goods';
    }
}