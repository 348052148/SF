<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class ShopDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'ismbao';
        $this->table = 'shop';
    }
}