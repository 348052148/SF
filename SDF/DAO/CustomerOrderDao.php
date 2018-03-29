<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class CustomerOrderDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'order';
        $this->table = 'customer_order';
    }
}