<?php
namespace SF\IOC;

class Proxy {

    public function __construct(\ReflectionClass $clsf,$instance)
    {
        $this->instance = $instance;

        $this->classRef = $clsf;
    }

    public function __call($name, $arguments)
    {
        $methodRef = $this->classRef->getMethod($name);

        $annoLst = $this->parseAnnotation($methodRef->getDocComment());

        $aopLst = [];

        $isAop = false;

        foreach ($annoLst as $a) {
            if ($a['method'] == 'Pointcut') {
                if (isset($a['parame']['value'])) {
                    $classProxy = $a['parame']['value'];
                    $advice = $a['parame']['advice'];

                    array_push($aopLst,['class'=>$classProxy,'advice'=>$advice]);

                    $isAop = true;
                    break;
                }
            }
        }
        //todo 前置增强
        if ($isAop){
            foreach ($aopLst as $aop){

                if(preg_match("/BEFORE/",$aop['advice'])||preg_match("/AROUND/",$aop['advice'])){

                    $this->aopClass = new \ReflectionClass($aop['class']);

                    $this->aopInstance = $this->aopClass->newInstanceArgs([]);

                    call_user_func_array([$this->aopInstance, 'before'], []);
                }

            }
        }

        try{
            $result = call_user_func_array([$this->instance,$name],$arguments);
        }catch (\Exception $exception){
            //todo 异常 增强
            if ($isAop){
                foreach ($aopLst as $aop){
                    if(preg_match("/THROW/",$aop['advice'])){

                        $this->aopClass = new \ReflectionClass($aop['class']);

                        $this->aopInstance = $this->aopClass->newInstanceArgs([]);

                        call_user_func_array([$this->aopInstance, 'throw'], []);
                    }

                }
            }
        }finally{
            //todo 最终增强
            if ($isAop){
                foreach ($aopLst as $aop){
                    if($aop['advice'] == 'FINALLY'){

                        $this->aopClass = new \ReflectionClass($aop['class']);

                        $this->aopInstance = $this->aopClass->newInstanceArgs([]);

                        call_user_func_array([$this->aopInstance, 'finally'], []);
                    }

                }
            }
        }
        //todo 后置增强
        if ($isAop){
            foreach ($aopLst as $aop){
                if(preg_match("/AFTER/",$aop['advice'])||preg_match("/AROUND/",$aop['advice'])){

                    $this->aopClass = new \ReflectionClass($aop['class']);

                    $this->aopInstance = $this->aopClass->newInstanceArgs([]);

                    call_user_func_array([$this->aopInstance, 'after'], []);
                }

            }
        }


        return $result;
    }

    private function parseAnnotation($annotaDoc){
        $annotaDocLst = explode("\n",$annotaDoc);
        $annotaLst = [];
        foreach ($annotaDocLst as $aDoc){
            $anno  = [];

            //有参方式
            if(preg_match('/\S\s@(\w+)\s?\((.+)\)/',$aDoc,$match)){
                if(count($match)>2) {
                    $anno['method'] = $match[1];
                    $prames = explode(',', $match[2]);
                    foreach ($prames as $p) {

                        if(preg_match('/(\w+)=["|\'](.+)["|\']/', $p, $pa)){
                            if(count($pa)!=3){
                                continue;
                            }
                            @$anno['parame'][$pa[1]] = $pa[2];
                        }
                        // 无参情况
                        elseif(preg_match('/["|\'](.+)["|\']/', $p, $pa)){
                            @$anno['parame']['value'] = $pa[1];
                        }
                    }
                    array_push($annotaLst, $anno);
                }
            }
            //无参方式
            if(preg_match('/\S\s@(\w+)\s?$/',$aDoc,$match)){
                $anno['method'] = $match[1];
                $anno['parame'] = [];
                array_push($annotaLst, $anno);
            }

        }
        return $annotaLst;
    }
}