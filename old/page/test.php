<?php 
require_once("config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');
?>

<?php
$i=0;
$nc = new sqlhelper();
$opecode = $nc->connect();
if($opecode!=0){
    die("error:::100:::网络连接错误");
}
$now = date("Y-m-d H:i:s");

$giftname="";
$activityname = "GD_TYW";
while($i<4000){
    $key = strtoupper("TYW".substr(md5(uniqid()),0,5));
    if($i<30){
        $giftname="奶茶(口味任选)";
        echo "----------$giftname------------<br>";
    }else if($i<60){
        $giftname="奶昔(口味任选)";
        echo "----------$giftname------------<br>";
    }else if($i<90){
        $giftname="芒果果冻5折券";
        echo "----------$giftname------------<br>";
    }else if($i<120){
        $giftname="巧克力布丁5折券";
        echo "----------$giftname------------<br>";
    }else{
        $giftname="任意单品8折券";
        echo "----------$giftname------------<br>";
    }
    $result = $nc ->mysql("insert into registration_info(activityname,name,handinmoneykey,formstatus)
                            values('$activityname','$giftname','$key','未兑换')");
    if(!$result){
        die("error:::050:::提交错误");
    }
    echo $key."<br>";
    $i++;
}

?>