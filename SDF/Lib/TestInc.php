<?php
require_once "Inc.php";
class TestM {
    public function ep(){
        $inc = new IncM();
        $inc->inc();
    }
}
