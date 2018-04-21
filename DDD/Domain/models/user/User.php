<?php
namespace Domain\models\user;
class User {
    private $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }
}