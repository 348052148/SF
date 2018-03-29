<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class DetailAccountDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'finance';
        $this->table = 'detail_account';
    }
}