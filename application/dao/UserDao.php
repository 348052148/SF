<?php
namespace dao;

use SF\ORM\Artemis;

class UserDao extends Artemis {

    public function __setOption()
    {
        $this->databses = 'base';
        $this->table = 'enterprise';
    }
}