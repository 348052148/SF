<?php
namespace SF\DB;

class ConnectionFactory {

    public static function connection(){
        return new MongoConnection();
    }
}