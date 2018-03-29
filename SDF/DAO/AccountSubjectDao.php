<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class AccountSubjectDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'finance';
        $this->table = 'account_subject';
    }
}