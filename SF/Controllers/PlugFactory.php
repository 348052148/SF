<?php
namespace SF\Controllers;

class PlugFactory {

    public static function getPlugs(){

        return [
            JsonPlugs::class
        ];
    }
}