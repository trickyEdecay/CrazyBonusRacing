<?php

/**
 * 后端向前端发送消息时，若有错误会发送这个错误消息包
 * User: trickyEdecay
 * Date: 2017/4/25
 * Time: 20:42
 */
class errorpack {

    public $info = 0;

    public $code = "";

    public function __construct() {
    }

    /**
     * 生成一个json格式的错误消息包
     * @return string
     */
    public function parsePack(){
        $pack = new stdClass();
        $pack->{'info'} = $this->info;
        $pack->{'code'} = $this->code;
        return json_encode($pack,JSON_UNESCAPED_UNICODE);
    }
}