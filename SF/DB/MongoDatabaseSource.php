<?php
namespace SF\DB;

use SF\DB\Driver\DriverFactory;

class MongoDatabaseSource implements IDataBaseSource{

    public static function getDirver()
    {

    }

    public static function getConnection($database,$table)
    {
        $driverClass = DriverFactory::getDriver();


        $driverRef = new \ReflectionClass($driverClass);


        $driverInstance = $driverRef->newInstanceArgs([$table,$database]);


        $connection = ConnectionFactory::connection();


        $connection->setDriver($driverInstance);

        return $connection;
    }
}