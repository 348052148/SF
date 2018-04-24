<?php
namespace DDD\Domain\goods;

class Goods {
    private $id;
    private $sku;
    private $title;
    private $subTitle;
    private $pic;
    private $specificat; // 规格
    private $salePrice;
    private $originalPrice; //原价
    private $discountLst;

    public function __construct()
    {

    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

}