<?php
namespace aop;


/**
 * Class LogginAop
 * @package aop
 * @aspect
 */
class LogginAop {

    /**
     * @pointcut (value='sercices\OrderService.test')
     */
    public function allMethod(){

    }

    /**
     * @pointcut (value='controllers\$.$')
     */
    public function toMethod(){

    }

    /**
     * @before (value='toMethod')
     */
    public function init1(){
        echo '$'.PHP_EOL;
    }

    /**
     * @before (value='allMethod')
     */
    public function init(){
        echo 'INIT';
    }

    /**
     * @after (value='allMethod')
     */
    public function dome(){
        echo PHP_EOL.'ENDI';
    }
}