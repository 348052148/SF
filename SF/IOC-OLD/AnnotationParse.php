<?php
namespace SF\IOC;

use SF\Annotations\ClassAnnotation;
use SF\Annotations\MethodAnnotation;

class AnnotationParse {
    private $aopMap = [];
    public function __construct($beanContainer)
    {
        $this->aopMap = [
          'pointcut'=>[],
          'before' => [],
          'after'  => []
        ];
        foreach ($beanContainer as $id=>$classInfo){
            $classAnnotation = new ClassAnnotation($classInfo['class']);

            if($classAnnotation->hasAnnotation('@aspect')){

                $methodAnnotation = new MethodAnnotation($classInfo['class']);

                if(!$methodAnnotation->hasAnnotation('@pointcut')){
                    continue;
                }

                $pointcutLst = $methodAnnotation->annotation('@pointcut');


                foreach ($pointcutLst as $pointcut){
                    if(!empty($pointcut['parame']['value'])){
                        $parameInfo = $this->parse($pointcut['parame']['value']);
                        //关注点
                        @$this->aopMap['pointcut'][@$pointcut['method']] = $parameInfo;

                    }
                }

                //BEFORE
                $beforeLst = $methodAnnotation->annotation('@before');

                foreach ($beforeLst as $before){
                    if(!empty($before['parame']['value'])){
                        //befere关注点
                        @$this->aopMap['before'][@$before['parame']['value']] = $before['class'].'.'.$before['method'];
                    }
                }

                //AFTER
                $afterLst = $methodAnnotation->annotation('@after');

                foreach ($afterLst as $after){
                    if(!empty($after['parame']['value'])){
                        //after关注点
                        @$this->aopMap['after'][@$after['parame']['value']] = $after['class'].'.'.$after['method'];
                    }
                }

            }
        }
    }

    /**
     * 查找类相关的Aop
     * @param $class
     * @return array
     */
    public function findClass($class){
        $arr = [];
        foreach ($this->aopMap['pointcut'] as $point => $item){
            //匹配到类
            if(strpos($class,str_replace('$','',$item['class']))!==false){

                $arr[$item['method']][$point] = [
                    'before'=>$this->aopMap['before'][$point],
                    'after' => isset($this->aopMap['after'][$point])?$this->aopMap['after'][$point]:[]
                ];

            }
        }
        return $arr;
    }

    private function parse($str){
        $class = explode('.',$str)[0];
        $method = explode('.',$str)[1];
        return [
            'class' => $class,
            'method' => $method
        ];
    }
}