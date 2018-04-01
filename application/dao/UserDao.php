<?php
namespace dao;

use SF\ORM\Artemis;

class UserDao extends Artemis {

    public function __setOption()
    {
        $this->databses = 'user1';
        $this->table = 'user1';
    }
}