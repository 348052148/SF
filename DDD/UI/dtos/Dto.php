<?php
namespace UI\dtos;
/**
 * 数据转化对象
 * Class Dto
 * @package UI\dtos
 */
class Dto {

    private $attr =[];

    public function __set($name, $value)
    {
        $this->attr[$name] = $value;
    }

    public function __get($name)
    {
        return $this->attr[$name];
    }

}