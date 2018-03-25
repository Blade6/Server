<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Home\Event;
/**
 * Description of JsonEvent
 *
 * @author jianhong
 */
class JsonEvent {
   
   // 封装数据，返回数据和returnCode
    public function encapsulate($data) {
        if (count($data) > 0) {
            $json['returnCode'] = "succ";
            $json['data'] = $data;
        }
        else {
            $json['returnCode'] = "fail";
            $json['data'] = "";
        }
        return $json;
    }
    
}
