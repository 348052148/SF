<?php
namespace SF\Route;

class Route {
    public function parseBaseUrl(){
        if(empty($_GET['s'])){
            $_GET['s'] = 'Index/index';
        }
        $pathInfo = explode('/', trim($_GET['s'],'/'));
        $pos = stripos(trim($_GET['s'],'/'),'/');
        return [
            'class' => !empty($pathInfo[0])?$pathInfo[0]:'Index',
            'pathinfo' => !empty(substr(trim($_GET['s']),$pos))?substr(trim($_GET['s'],'/'),$pos):'index'
        ];
    }

    public function parsePathUrl(){
        if(empty($_GET['s'])){
            $_GET['s'] = 'Index/index';
        }
        $pathInfo = explode('/', trim($_GET['s'],'/'));
        return [
            'class' => !empty($pathInfo[0])?$pathInfo[0]:'Index',
            'pathinfo' => $_GET['s']
        ];
    }

    public function parseUrl(){
        $controller = 'Index';
        $action = 'index';
        $parames = [];
        //
        $pathInfo = [];
        if(!empty($_GET['s'])){
            $pathInfo = explode('/', trim($_GET['s'],'/'));
        }
        //
        switch (count($pathInfo)){
            case 1:
                $controller = $pathInfo[0];
                break;
            case 2:
                $controller = $pathInfo[0];
                $action = $pathInfo[1];
                break;
            case 3:
                $controller = $pathInfo[0];
                $action = $pathInfo[1];
                $parames = array_slice($pathInfo,2);
                break;
            default:

        }
        return [
            'controller' => $controller,
            'action' => $action,
            'parames' => $parames
        ];
    }
}