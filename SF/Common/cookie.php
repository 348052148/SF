<?php
namespace SDF\Common;
use \SDF\Core\Config;
/**
 * author: yuanji
 * Date: 2015年10月12日04:38:57
 * cookie处理类
 */
class Cookie {
    /**
     * 设置cookie
     * @param $name
     * @param string $value
     * @param string $expire
     * @param string $domain
     * @param string $path
     * @param string $prefix
     * @param bool|FALSE $secure
     * @param bool|FALSE $httponly
     */
    public static function set_cookie($name, $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE, $httponly = FALSE){
        if (is_array($name)){
            // always leave 'name' in last place, as the loop will break otherwise, due to $$item
            foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'httponly', 'name') as $item){
                if (isset($name[$item])){
                    $$item = $name[$item];
                }
            }
        }
        if ($prefix === '' && Config::getField('cookie','cookie_prefix') !== ''){
            $prefix = Config::getField('cookie','cookie_prefix');
        }

        if ($domain == '' && Config::getField('cookie','cookie_domain') != ''){
            $domain = Config::getField('cookie','cookie_domain');
        }

        if ($path === '/' && Config::getField('cookie','cookie_path') !== '/'){
            //$path = config_item('cookie_path');
            $path = Config::getField('cookie','cookie_path');
        }

        if ($secure === FALSE && Config::getField('cookie','cookie_secure') === TRUE){
            //$secure = config_item('cookie_secure');
            $secure = Config::getField('cookie','cookie_secure');
        }

        if ($httponly === FALSE && Config::getField('cookie','cookie_httponly') !== FALSE){
            //$httponly = config_item('cookie_httponly');
            $httponly  = Config::getField('cookie','cookie_httponly');
        }

        if ( ! is_numeric($expire)){
            $expire = time() - 86500;
        }else{
            $expire = ($expire > 0) ? time() + $expire : 0;
        }
        setcookie($prefix.$name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * 获取cookie
     * @param $index
     * @param null $xss_clean
     * @return mixed
     * @throws \Exception
     */
    public static function get_cookie($index , $xss_clean = NULL){
        $prefix = isset($_COOKIE[$index]) ? '' : Config::getField('cookie','cookie_prefix');
        return self::_fetch_from_array($_COOKIE, $prefix.$index, $xss_clean);
    }
    /**
     * Fetch from array
     *
     * Internal method used to retrieve values from global arrays.
     *
     * @param	array	&$array		$_GET, $_POST, $_COOKIE, $_SERVER, etc.
     * @param	mixed	$index		Index for item to be fetched from $array
     * @return	mixed
     */
    public static function _fetch_from_array(&$array, $index = NULL){
        // If $index is NULL, it means that the whole $array is requested
        isset($index) OR $index = array_keys($array);

        // allow fetching multiple keys at once
        if (is_array($index)){
            $output = array();
            foreach ($index as $key){
                $output[$key] = self::_fetch_from_array($array, $key);
            }
            return $output;
        }

        if (isset($array[$index])){
            $value = $array[$index];
        }elseif (($count = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $index, $matches)) > 1){// Does the index contain array notation
            $value = $array;
            for ($i = 0; $i < $count; $i++){
                $key = trim($matches[0][$i], '[]');
                if ($key === '') {// Empty notation will return the value as array
                    break;
                }
                if (isset($value[$key])){
                    $value = $value[$key];
                }else{
                    return NULL;
                }
            }
        }else{
            return NULL;
        }
        return $value;
    }

    /**
     * 删除指定cookie
     * @param $name
     * @param string $domain
     * @param string $path
     * @param string $prefix
     */
    public static function delete_cookie($name, $domain = '', $path = '/', $prefix = ''){
        set_cookie($name, '', '', $domain, $path, $prefix);
    }
}
