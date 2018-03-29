<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class SellMinusDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'ismbao';
        $this->table = 'sell_minus';
    }
}