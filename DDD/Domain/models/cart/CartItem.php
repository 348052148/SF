<?php
namespace DDD\Domain\models\cart;

class CartItem {
    private $goods;
    private $goodsId;
    private $num;

    public function __construct($goods,$num)
    {
        $this->goods = $goods;
        $this->goodsId = $goods->id;
        $this->num = $num;
    }

}