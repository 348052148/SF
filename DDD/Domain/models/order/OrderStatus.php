<?php
namespace DDD\Domain\order;
/**
 * 值对象订单状态
 * Class OrderStatus
 */
class OrderStatus {

    const ORDER_CREARE = 0; //创建

    const ORDER_PAY = 1; //支付

    const ORDER_SEND = 2; //配送

    const ORDER_ARRV = 3; //送达

    const ORDER_CANCEL = 4; //取消

    const ORDER_COMPALATE = 5; //完成

    const ORDER_AFTERSALE = 6; //售后
}