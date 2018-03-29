<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15 0015
 * Time: 下午 5:35
 */
namespace SDF\DAO;

use SDF\ORM\Artemis;

class OrderCommentDao extends Artemis
{
    protected function setOption()
    {
        $this->database = 'ismbao';
        $this->table = 'order_comment';
    }
}