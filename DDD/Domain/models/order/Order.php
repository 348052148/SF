<?php
namespace DDD\Domain\order;

use DDD\Domain\goods\Goods;

class Order {
    private $id;
    private $ctime;
    private $status; //0  1 待支付，2 待发货，3 待送到 4 待评价
    private $goodsLst = [];
    private $orderPrice = 0;
    private $payType;
    private $user; // 购买者

    public function __construct($id="")
    {
        if($id!="") $this->id = $id;

    }

    public function addGoods(Goods $goods){
        array_push($this->goodsLst,$goods);
        $this->calucePrice();
    }

    private function calucePrice(){
        $price = 0;
        foreach ($this->goodsLst as $goods){
            $price += $goods->salePrice;
        }
        $this->orderPrice = $price;
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