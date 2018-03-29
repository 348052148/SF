<?php
namespace controllers;

/**
 * Class IndexController
 * @package controllers
 * @controller
 */
class IndexController extends \SDF\Controllers\BaseController{

    /**
     * @point (value='123')
     * @route (value='/{id}')
     */
    public function index($req,$res,$id){
        echo 'WEL:'.$id;
    }

    /**
     * @point (value='123')
     * @route (value='/index/{dome}')
     */
    public function index1($req,$res,$id){
        echo 'Index:'.$id;
    }
}