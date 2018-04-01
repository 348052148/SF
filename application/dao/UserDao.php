<?php
namespace dao;

use SF\ORM\AbsArtemis;

class UserDao extends AbsArtemis {

    public function __construct()
    {
        parent::__construct('1', '2');
    }

    protected function add(){
        echo '123';
    }

}