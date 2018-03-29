<?php
namespace SDF\DAO;

use SDF\SE\Athena;

class GoodsSearchDao extends Athena{

    public function setOption()
    {
        $this->tables = 'goods';
    }
}