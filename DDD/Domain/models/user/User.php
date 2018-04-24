<?php
namespace DDD\Domain\user;

use DDD\Domain\cart\Cart;
use DDD\Domain\order\Order;

class User {
    private $id;
    private $username;
    private $nickname;
    private $passwd;
    private $ctime;
    private $status;
    private $cart;

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

    public function login(){
        $this->status = 1;
    }

    public function logout(){
        $this->status = 0;
    }

    public function createOrder($goodsLst){

        $order = new Order($this);

        $order->user = $this;

        foreach ($goodsLst as $goods){
            $order->addGoods($goods);
        }

        return $order;
    }

    public function payOrder(Order $order,$payType){
        $order->payType = $payType;
        return $order;
    }

    public function createCart(){
        $this->cart = new Cart();
    }
}