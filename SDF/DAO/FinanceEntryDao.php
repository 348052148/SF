<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class FinanceEntryDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'finance';
        $this->table = 'finance_entry';
    }
}