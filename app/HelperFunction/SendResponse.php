<?php

class HelperFunction{
    public static function SendResponse($status, $message, $data){
        return [
            "success"=>$status,
            "message"=>$message,
            "data"=>$data
        ];
    }
}