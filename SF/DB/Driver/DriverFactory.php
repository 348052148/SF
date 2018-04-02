<?php
namespace SF\DB\Driver;

use SF\DB\Driver\MongoDB\MongoCollection;
use SF\DB\Driver\MongoDB\MongoDriver;

class DriverFactory {

    public static function getDriver(){
        return MongoDriver::class;
    }
}