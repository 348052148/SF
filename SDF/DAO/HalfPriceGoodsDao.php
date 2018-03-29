<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class HalfPriceGoodsDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'sell';
        $this->table = 'half_price_goods';
    }
}