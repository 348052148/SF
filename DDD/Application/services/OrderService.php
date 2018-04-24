<?php
namespace DDD\Application\services;

use DDD\Domain\order\Order;
use DDD\Domain\repositorys\GoodsRepository;
use DDD\Domain\repositorys\OrderRepository;
use DDD\Domain\user\Buyer;
use UI\dtos\RequestDto;

class OrderService {

    //创建订单
    public function createOrder(RequestDto $orderDto){

        $orderRepository = new OrderRepository();

        $buyer = $orderRepository->findById($orderDto->uid);

        $goodsLst = [];

        $goodsRepository = new GoodsRepository();

        foreach ($orderDto->skus as $sku){
            $goods = $goodsRepository->findById($sku);
            array_push($goodsLst,$goods);
        }

        $order = $buyer->createOrder($goodsLst);

        $orderRepository = new OrderRepository();

        $orderRepository->store($order);

        return $order;
    }

    public function payOrder(RequestDto $orderDto){
        $orderRepository = new OrderRepository();

        $buyer = $orderRepository->findById($orderDto->uid);

        $orderRepository = new OrderRepository();
        $order = $orderRepository->findById($orderDto->id);

        $order = $buyer->payOrder($order,1);

        $orderRepository->store($order);

        return $order;
    }
    //其他角色进行配送
    public function deliveryOrder(RequestDto $orderDto){

    }
    //送达
    public function complateOrder(RequestDto $orderDto){

    }
}