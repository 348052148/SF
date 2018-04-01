<?php
/**
 * 取得根域名
 *
 * @author lonely
 * @create 2011-3-11
 * @version 0.1
 * @lastupdate lonely
 * @package Sl
 */
namespace SDF\Common;

class RootDomain{
    private static $self;
    private $domain=null;
    private $host=null;
    private $state_domain;
    private $top_domain;
    /**
     * 取得域名分析实例
     * Enter description here ...
     */
    public static function instace(){
        if(!self::$self)
            self::$self=new self();
        return self::$self;
    }
    private function __construct(){
        $this->state_domain=array(
            'al','dz','af','ar','ae','aw','om','az','eg','et','ie','ee','ad','ao','ai','ag','at','au','mo','bb','pg','bs','pk','py','ps','bh','pa','br','by','bm','bg','mp','bj','be','is','pr','ba','pl','bo','bz','bw','bt','bf','bi','bv','kp','gq','dk','de','tl','tp','tg','dm','do','ru','ec','er','fr','fo','pf','gf','tf','va','ph','fj','fi','cv','fk','gm','cg','cd','co','cr','gg','gd','gl','ge','cu','gp','gu','gy','kz','ht','kr','nl','an','hm','hn','ki','dj','kg','gn','gw','ca','gh','ga','kh','cz','zw','cm','qa','ky','km','ci','kw','cc','hr','ke','ck','lv','ls','la','lb','lt','lr','ly','li','re','lu','rw','ro','mg','im','mv','mt','mw','my','ml','mk','mh','mq','yt','mu','mr','us','um','as','vi','mn','ms','bd','pe','fm','mm','md','ma','mc','mz','mx','nr','np','ni','ne','ng','nu','no','nf','na','za','aq','gs','eu','pw','pn','pt','jp','se','ch','sv','ws','yu','sl','sn','cy','sc','sa','cx','st','sh','kn','lc','sm','pm','vc','lk','sk','si','sj','sz','sd','sr','sb','so','tj','tw','th','tz','to','tc','tt','tn','tv','tr','tm','tk','wf','vu','gt','ve','bn','ug','ua','uy','uz','es','eh','gr','hk','sg','nc','nz','hu','sy','jm','am','ac','ye','iq','ir','il','it','in','id','uk','vg','io','jo','vn','zm','je','td','gi','cl','cf','cn','yr'
        );
        $this->top_domain=array('com','arpa','edu','gov','int','mil','net','org','biz','info','pro','name','museum','coop','aero','xxx','idv','me','mobi');
        $this->url=$_SERVER['HTTP_HOST'];
    }
    /**
     * 设置URL
     * Enter description here ...
     * @param string $url
     */
    public function setUrl($url=null){
        $url=$url?$url:$this->url;
        if(empty($url))return $this;
        if(!preg_match("/^http::/is", $url))
            $url="http://".$url;
        $url=parse_url(strtolower($url));
        $urlarr=explode(".", $url['host']);
        $count=count($urlarr);
        if ($count<=2){
            $this->domain=array_pop($url);
        }else if ($count>2){
            $last=array_pop($urlarr);
            $last_1=array_pop($urlarr);
            if(in_array($last, $this->top_domain)){
                $this->domain=$last_1.'.'.$last;
                $this->host=implode('.', $urlarr);
            }else if (in_array($last, $this->state_domain)){
                $last_2=array_pop($urlarr);
                if(in_array($last_1, $this->top_domain)){
                    $this->domain=$last_2.'.'.$last_1.'.'.$last;
                    $this->host=implode('.', $urlarr);
                }else{
                    $this->host=implode('.', $urlarr).$last_2;
                    $this->domain=$last_1.'.'.$last;
                }
            }
        }
        return $this;
    }
    /**
     * 取得域名
     * Enter description here ...
     */
    public function getDomain(){
        return $this->domain;
    }
    /**
     * 取得主机
     * Enter description here ...
     */
    public function getHost(){
        return $this->host;
    }
}