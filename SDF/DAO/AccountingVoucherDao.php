<?php
namespace SDF\DAO;


use SDF\ORM\Artemis;

class AccountingVoucherDao extends Artemis {

    protected function setOption()
    {
        $this->database = 'finance';
        $this->table = 'accounting_voucher';
    }
}