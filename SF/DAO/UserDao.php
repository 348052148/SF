<?php
namespace SF\DAO;


use SF\ORM\Artemis;

class UserDao extends Artemis {

    protected function setOption()
    {
        self::$database = 'lostapp';
        self::$table = 'user';
    }
}