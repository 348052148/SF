<?php
namespace SF\ORM;

/**
 * Orm 基础
 * Class Artemis
 * @package SF\ORM
 */
abstract class Artemis extends AbsArtemis {

    protected $table;

    protected $database;

    public function __construct()
    {
        $this->setOption();
        parent::__construct($this->table,$this->database);
    }

    abstract protected function setOption();

}