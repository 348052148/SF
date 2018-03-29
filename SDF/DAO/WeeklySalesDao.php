<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class WeeklySalesDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'sell';
        $this->table = 'weekly_sales';
    }
}