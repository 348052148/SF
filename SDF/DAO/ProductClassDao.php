<?php
namespace SDF\DAO;

use SDF\ORM\Artemis;

class ProductClassDao extends Artemis{
    protected function setOption()
    {
        $this->database = 'ismbao';
        $this->table = 'product_class';
    }
}