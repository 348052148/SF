<?php
/**
 * Created by PhpStorm.
 * User: ji
 * Date: 2014/10/27
 * Time: 15:47
 */
namespace SF\View;
use SF\Core\Config;
class View{
    public static function display($tplFile='',array $data=array()){
        $tplPath = Config::getField('project', 'tpl_path', \SF\SF::getRootPath() . 'template' . DS . 'default'. DS);
        //$tplFile = \str_replace('\\',DS,$tplFile);
        //$tplFile = $tplFile==''?$GLOBALS['PARSE_INFO']['control'].DS.$GLOBALS['PARSE_INFO']['action']:$tplFile;
        $fileName = $tplPath . $tplFile.'.php';
        if (!\is_file($fileName)) {
            throw new \Exception("no file {$fileName}",404);
        }
        if(!empty($data)){
            \extract($data);
        }
        include "{$fileName}";
    }
}