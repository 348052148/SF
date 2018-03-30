<?php
namespace controllers;
/**
 * Class IndexController
 * @package controllers
 *
 */
class WorkOrderController extends \SDF\Controllers\BaseController{

    public function __construct()
    {

    }

    /**
     * 获取交易列表
     * @Route (value='/orders',method='GET')
     */
    public function orderLst(){

    }

    /**
     * 获取一条交易信息
     * @Route (value='/orders/{id}',method='GET')
     */
    public function getOrderById(){

    }

    /**
     * 更新交易信息
     * @Route (value='orders',method='POST')
     */
    public function updateOrderById(){

    }

}