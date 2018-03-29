<?php
namespace SDF\Annotations;

class ClassAnnotation implements Annotation{

    private $classAnnotation  = [];

    private $class;

    private $classAnnotationMode;

    public function __construct($class)
    {
        $this->class = $class;
        $this->classAnnotationMode = [
            '@controller','@aspect'
        ];

        $this->parse($class);
    }

    public function getController(){
        return $this->class;
    }

    public function isController(){
        return $this->hasAnnotation('@controller');
    }

    public function setClassAnnotationMode($annotationMode){
        $this->classAnnotationMode = $annotationMode;
    }

    public function hasAnnotation($annotation){
        if(isset($this->classAnnotation[$annotation])){
            return true;
        }
        return false;
    }

    public function getAnnotation(){
        return $this->classAnnotation;
    }

    public function parse($class){

        $classRef = new \ReflectionClass($class);

        $annotationDoc = $classRef->getDocComment();

        $annotationDocLst = explode("\n",$annotationDoc);

        foreach ($annotationDocLst as $annotationStr){
            $match = $this->match($annotationStr);
            if($match!==false&&in_array($match['mode'],$this->classAnnotationMode)){
                $this->classAnnotation[$match['mode']][] = [
                    'class' => $class,
                    'parame' => $match['parame']
                ];
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