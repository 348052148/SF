<?php
namespace SF\Http;

class Request {

    /**
     * 返回请求方式
     */
    public function method(){
        if(isset($_SERVER['REQUEST_METHOD']))
            return $_SERVER['REQUEST_METHOD'];
        return 'GET';
    }

    /**
     * 获取跟url
     */
    public function root(){
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * 获取url 不包括 query
     */
    public function url(){

    }

    /**
     * 获取所有
     */
    public function fullUrl(){

    }

    /**
     * 获取当前解析的pathinfo
     */
    public function path(){

    }

    /**
     * 获取ajax
     */
    public function ajax(){

    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     */
    public function query(string $key = null, mixed $default = null){
        if(isset($_REQUEST[$key])){
            return $_REQUEST[$key];
        }
        return $default;
    }

    /**
     * @param string $key
     */
    public function hasCookie(string $key){
        return isset($_COOKIE[$key]);
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     */
    public function cookie(string $key = null, mixed $default = null){
        if(isset($_REQUEST[$key])){
            return $_REQUEST[$key];
        }
        return $default;
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     */
    public function file(string $key = null, mixed $default = null){
        if(isset($_FILES[$key])){
            return $_FILES[$key];
        }
        return $default;
    }

    /**
     * @param string $key
     */
    public function hasFile(string $key){
        return isset($_FILES[$key]);
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     */
    public function header(string $key = null, mixed $default = null){
        $headers = [];
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        if(isset($headers[$key])){
            return $headers[$key];
        }
        return $default;
    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     */
    public function server(string $key = null, mixed $default = null){

    }

    /**
     * @param string|null $key
     * @param mixed|null $default
     */
    public function json(string $key = null, mixed $default = null){

    }
}