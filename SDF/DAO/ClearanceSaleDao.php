<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class ClearanceSaleDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'sell';
        $this->table = 'clearance_sale';
    }
}