<?php
namespace SF\ORM;

class ArtemisFactory {

    public static function artemis(){
        return MongoArtemis::class;
    }
}