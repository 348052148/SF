<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class GiftsManagementDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'goods';
        $this->table = 'gifts_management';
    }
}