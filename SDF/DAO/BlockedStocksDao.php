<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class BlockedStocksDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'stocks';
        $this->table = 'blocked_stocks';
    }
}