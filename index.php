<?php
/**
 * 项目入口文件名
 * User: ji
 * Date: 2014/10/21
 * Time: 14:07
 */

#设置编码
header('Content-Type: text/html; charset=UTF-8');
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
define('ENVIRONMENT', 'development');
if (defined('ENVIRONMENT')){
    switch (ENVIRONMENT){
        case 'development':
            error_reporting(E_ALL);
            break;
        case 'testing':
        case 'production':
            error_reporting(0);
            break;
        default:
            exit('应用程序环境没有被正确设置。');
    }
}

#入口文件名
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
#项目根目录
define('FCPATH', str_replace(SELF, '', __FILE__));

include "SF/SF.php";
//$app = new \SF\Core\ServiceApplication('application','services');
$summer = \SF\IOC\SummerFactory::getArrayContext();
//
$app  = $summer->get('WebApplication','SF');
$app->run();
