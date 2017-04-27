<?php
/**
 * User: trickyEdecay
 * Date: 2017/4/26
 * Time: 21:14
 */
require_once ("checkString.php");
require_once ("encrypt.php");

//检查登录状态
function checkCAQlogin(){
    session_start();
    $errPack = new errorpack();
    if(isset($_COOKIE['auth'])){ //先判断cookie存在与否
        list($peopleId, $name,$randomKey) = explode(':', $_COOKIE['auth']);
        $_SESSION['randomkey']=$randomKey; //使用randomkey来记录登录状态
        $_SESSION['name']=$name; //使用name来记录登录状态
        $_SESSION['peopleid']=$peopleId; //使用name来记录登录状态
    }


    if(!isset($_SESSION['randomkey'])){
        $errPack->code = 0004;
        $errPack->info = "你的登录信息已经过期~请重新登录";
        die($errPack->parsePack());
    }else{
        $db_player = new player();
        if(!$db_player->connect()){
            $errPack->code = 0001;
            $errPack->info = "居然连接失败了~请刷新页面重试";
            die($errPack->parsePack());
        }

        $row = $db_player->getInfo($_SESSION['peopleid'],"`randomkey`,`isbanned`");
        $randomKey = $row['randomkey'];
        $isBanned = $row['isbanned'];

        if($randomKey != $_SESSION['randomkey']){
            $errPack->code = 0002;
            $errPack->info = "你的账号在别的地方登录了哦~刷新重试";
            $db_player->close();
            die($errPack->parsePack());
        }

        if($isBanned !=0){
            $errPack->code = 0003;
            $errPack->info = "你的账号已经被封禁!请与工作人员联系!";
            $db_player->close();
            die($errPack->parsePack());
        }
    }

    $db_player->close();
    $errPack->code = 0000;
    $errPack->info = "验证成功";
    return $errPack;
}