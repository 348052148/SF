<?php
namespace DDD\Domain\order;
/**
 * 值对象 订单支付类型
 * Class PayStatus
 */
class PayStatus{
    const PAY_XJ = 0;
    const PAY_WX = 1;
    const PAY_AliPAY = 2;
}