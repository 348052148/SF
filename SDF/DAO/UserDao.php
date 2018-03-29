<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class UserDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'lostapp';
        $this->table = 'user';
    }
}