<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class CartDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'ismbao';
        $this->table = 'cart';
    }


}