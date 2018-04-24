<?php
namespace DDD\Domain\cart;

use DDD\Domain\goods\Goods;
use DDD\Domain\models\cart\CartItem;

class Cart {
    private $id;
    private $goodsLst = [];

    public function addGoods(Goods $goods,$num){
        array_push($this->goodsLst,new CartItem($goods,$num));
    }

    public function removeGoods($goodsId){
        $removeIndex = null;
        foreach ($this->goodsLst as $i=> $goodsItem){
            if($goodsItem.goodsId == $goodsId ){
                $removeIndex = $i;
            }
        }
        if($removeIndex != null)
        array_splice($this->goodsLst,$removeIndex,1);
    }

    public function changeGoodsNum($goodsId,$num){
        $changeIndex = null;
        foreach ($this->goodsLst as $i=> $goodsItem){
            if($goodsItem.goodsId == $goodsId ){
                $goodsItem->num = $num;
            }
        }
    }



}