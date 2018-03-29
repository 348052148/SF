<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class OrderPayDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'order';
        $this->table = 'order_pay';
    }
}