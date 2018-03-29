<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class ProductDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'ismbao';
        $this->table = 'product';
    }
}