<?php
namespace SF\DAO;


use SF\ORM\Artemis;

class UserDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'lostapp';
        $this->table = 'user';
    }
}