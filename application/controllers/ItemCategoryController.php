<?php
namespace controllers;

use dao\CategoryDao;

class  ItemCategoryController extends \SF\Controllers\BaseController{

    /**
     * @Route ("/categorys")
     */
    public function getCategory(){

        $categoryLst = CategoryDao::get()->toArray();

        foreach ($categoryLst as &$category){
            $category['id'] = $category['_id']."";
        }

       return $this->toJson($categoryLst);
    }

    /**
     * @Route ("/categorys",method="POST")
     */
    public function addCategory(){

        $data = [
            'name' => $_REQUEST['name'],
            'pic' => ''
        ];

        CategoryDao::insert($data);

        return $this->toJson([]);

    }

    /**
     * @Route ("infos")
     */
    public function infoType(){
        $list = [
               ['id'=>1, 'name' => '失物招领'],
                ['id'=>2, 'name' => '寻物启事']
            ];

        return $this->toJson($list);
    }
}