<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class AddressDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'fans';
        $this->table = 'address';
    }
}