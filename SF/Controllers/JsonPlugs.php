<?php
namespace SF\Controllers;

class JsonPlugs {

    public function toJson($data,$code=0,$msg='SUCCESS'){
        header('Access-Control-Allow-Origin:*');
        return json_encode([
            'code' => $code,
            'message' => $msg,
            'data' => $data
        ]);
    }
}