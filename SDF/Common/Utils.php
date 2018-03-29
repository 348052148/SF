<?php
/**
 * author: yuanji
 * Date: 2014年7月15日15:45:05
 * 公用方法类
 */

namespace SDF\Common;

class Utils {

    /**
     * 判断是否ajax方式
     * @return bool
     */
    public static function isAjax(){
        if (!empty($_REQUEST['ajax']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
            return true;
        }
        return false;
    }

    /**
     * @param $size
     * @return string
     */
    public static function formatBytes($size) {
        /*$units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;*/

        $units = array(' B', ' KB', ' MB', ' GB');
        for ($i = 0; $size >= 1024 && $i < 3; $i++) $size /= 1024;

        return round($size, 2).$units[$i];
    }

    /**
     * 获取ip地址
     * @return string
     */
    public static function GetIP() {
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        else if (!empty($_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        else if (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        }
        else if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        }
        else if (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        }
        else {
            $ip = "Unknown";
        }
        return $ip;
    }
    public static function StringSizeToBytes($Size){
        $Unit = strtolower($Size);
        $Unit = preg_replace('/[^a-z]/', '', $Unit);
        $Value = intval(preg_replace('/[^0-9]/', '', $Size));
        $Units = array('b'=>0, 'k'=>1, 'm'=>2, 'g'=>3, 't'=>4);
        $Exponent = isset($Units[$Unit]) ? $Units[$Unit] : 0;
        return ($Value * pow(1024, $Exponent));
    }

    /**
     * 删除字符串里面的Emoji表情字符
     * @param $text
     * @return mixed|string
     */
    public static function removeEmoji($text) {
        $clean_text = "";
        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, '', $text);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, '', $clean_text);

        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, '', $clean_text);

        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, '', $clean_text);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);

        return $clean_text;
    }
    public static function remove_emoji($text){
        return preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $text);
    }
    public static function emojiFilter($text){
        $text = json_encode($text);
        preg_match_all("/(\\\\ud83c\\\\u[0-9a-f]{4})|(\\\\ud83d\\\\u[0-9a-f]{4})|(\\\\u[0-9a-f]{4})/", $text, $matchs);
        if(!isset($matchs[0][0])) { return json_decode($text, true); }

        $emoji = $matchs[0];
        foreach($emoji as $ec) {
            $hex = substr($ec, -4);
            if(strlen($ec)==6) {
                if($hex>='2600' and $hex<='27ff') {
                    $text = str_replace($ec, '', $text);
                }
            } else {
                if($hex>='dc00' and $hex<='dfff') {
                    $text = str_replace($ec, '', $text);
                }
            }
        }
        return json_decode($text, true);
    }

    /**
     * 更具原图地址，格式化缩略图地址
     * @param $url
     * @param int $w
     * @param int $h
     * @param null $host
     * @return string
     * @throws \Exception
     */
    public static function format_thumb($url,$w=600,$h=600,$host = null){
        $arr = parse_url($url);
        $new_url = array(
            "scheme" => isset($arr['scheme'])?$arr['scheme']:'http',
            "host" => $host?$host:\SDF\Core\Config::get('thumb_host',@$arr['host']),
            "path" => isset($arr['path'])?$arr['path']:'404.png',
            "query" => isset($arr['query'])?$arr['query']:'',
            "fragment" => isset($arr['fragment'])?$arr['fragment']:'',
        );
        $new_url['path'] = "tb/{$w}x{$h}".$new_url['path'];
        $url = "{$new_url['scheme']}://{$new_url['host']}/{$new_url['path']}";
        return $url;
    }
}
