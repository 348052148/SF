<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class MemberDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'ismbao';
        $this->table = 'member';
    }
}