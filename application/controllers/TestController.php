<?php
namespace controllers;

/**
 * Class TestController
 * @package controllers
 * @Controller ('Test')
 */
class TestController extends \SF\Controllers\BaseController{

    /**
     * @return string
     * @Pointcut ('aop\LogginAop',advice='BEFORE')
     * @Route ('/tests')
     *
     */
    public function test(){
        return 'test1';
    }
}