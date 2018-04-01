<?php
namespace SF\Annotations;

class MethodAnnotation implements Annotation{

    private $methodAnnotation = [];

    private $parames = [];

    public function __construct($class)
    {
        $this->parse($class);
    }

    /**
     * @param $pathinfo
     * @return bool
     * 匹配规则  /hello/{id}
     */
    public function findAction($pathinfo){
        //$this->parsePath($pathinfo);
        if(@$this->methodAnnotation['@route'])
        foreach (@$this->methodAnnotation['@route'] as $annotation){
            if($this->parsePath($pathinfo,$annotation['parame']['value'])){
                return $annotation['method'];
            }
        }
        return false;
    }

    /**
     * 获取参数
     * @return array
     */
    public function getParames(){
        return $this->parames;
    }

    public function hasAnnotation($annotaMode){
        if(isset($this->methodAnnotation[$annotaMode])){
            return true;
        }
        return false;
    }

    public function annotation($annotaMode){
        return isset($this->methodAnnotation[$annotaMode])?$this->methodAnnotation[$annotaMode]:[];
    }

    /**
     * 解析路径
     * @param $pathinfo
     * @param $parttern
     * @return bool
     */
    private function parsePath($pathinfo,$parttern){
        //每次匹配清理值
        $this->parames = [];

        if(substr($pathinfo,0,1) == '/'){
            $pathinfo = substr($pathinfo,1);
        }
        if(substr($parttern,0,1) == '/'){
            $parttern = substr($parttern,1);
        }

        $Lparttern = explode('/',$pathinfo);
        $Rparttern = explode('/',$parttern);

        $rcount = count($Rparttern);

        for ($i=0;$i<count($Lparttern);$i++){
            if($i >= $rcount){
                return false;
            }
            // 如果是参数模式 则直接跳过
            if(preg_match('/^{\w+}$/',$Rparttern[$i])){
                array_push($this->parames,$Lparttern[$i]);
                continue;
            }
            if($Lparttern[$i] != $Rparttern[$i] ){
                return false;
            }
        }
        return true;
    }

    private function parse($class){

        $methodAnnotationMode = [
            '@route','@pointcut','@before','@after'
        ];


        $classRef = new \ReflectionClass($class);

        $methodRefLst = $classRef->getMethods(\ReflectionMethod::IS_PUBLIC|\ReflectionMethod::IS_PROTECTED);

        foreach ($methodRefLst as $methodRef){
            $annotationDocLst =  explode("\n",$methodRef->getDocComment());
            foreach ($annotationDocLst as $annotationStr){
                $match = $this->match($annotationStr);
                if($match!==false&&in_array($match['mode'],$methodAnnotationMode)){
                    $this->methodAnnotation[$match['mode']][] = [
                        'class' => $class,
                        'method' => $methodRef->getName(),
                        'parame' => $match['parame']
                    ];
                }
            }
        }
    }

    public  function match($annotationStr){

        $annotationStr = str_replace('*','',$annotationStr);


        $mode = explode(' ',trim($annotationStr))[0];

        $pos1 = stripos(trim($annotationStr),'(');

        if($pos1 === false){
            return [
                'mode' => $mode,
                'parame' => []
            ];
        }


        $parameStr = explode(',',substr(trim($annotationStr),$pos1+1,-1));

        $parames = [];

        foreach ($parameStr as $parame){
            $ps = explode('=',$parame);

            $key = trim($ps[0]);
            //这里需要判断字符串
            $val = str_replace('"','',str_replace("'",'',trim($ps[1])));

            $parames[$key] = $val;

        }

        return [
            'mode' => $mode,
            'parame' => $parames
        ];
    }
}