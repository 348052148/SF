<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class OrderDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'ismbao';
        $this->table = 'order';
    }
}