<?php
namespace SDF\Http;

use SDF\View\View;

class Response {

    public function view($view,$data){
        View::display($view,$data);
    }

    public function download($file){

    }

    public function json(){

    }

    public function header(string $key, string $value, bool $replace = true){

    }
}