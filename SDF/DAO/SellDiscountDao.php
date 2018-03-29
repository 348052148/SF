<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class SellDiscountDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'sell';
        $this->table = 'sell_discount';
    }
}