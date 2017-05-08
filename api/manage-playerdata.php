<?php //用来处理知识问答竞赛部分后台的细节?>
<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
?>

<?php
//判断操作数,分配到函数进行操作
header('Content-Type:text/html; charset=utf-8');//使用gb2312编码，使中文不会变成乱码
$what = $_POST['what'];
switch($what){
    case 'deban':
        deban($_POST['peopleid']);
        break;
    case 'clearWrongCaptchaCount':
        clearWrongCaptchaCount($_POST['peopleid']);
        break;
    case 'passive':
        passive($_POST['peopleid']);
        break;
    case 'clearactive':
        clearactive($_POST['peopleid']);
        break;
    case 'findprofile':
        findprofile($_POST['name'],$_POST['tel']);
        break;
}
?>


<?php
//解封账号
function deban($peopleid){
    $nc = new sqlihelper();
    $errPack = new errorpack();
    if(!$nc->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接错误";
        die($errPack->parsePack());
    }
    $result = $nc ->mysql("update question_people set isbanned=0,wrongidccount=0 where id='$peopleid'");
    if(!$result){
        $errPack->code = 0002;
        $errPack->info = "操作过程中断";
        $nc->close();
        die($errPack->parsePack());
    }

    $errPack->code = 0000;
    $errPack->info = "成功";
    $nc->close();
    die($errPack->parsePack());
}

//清零验证码输错次数
function clearWrongCaptchaCount($playerId){
    $nc = new sqlihelper();
    $errPack = new errorpack();
    if(!$nc->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接错误";
        die($errPack->parsePack());
    }
    $result = $nc ->mysql("update question_people set wrongidccount=0 where id='$playerId'");
    if(!$result){
        $errPack->code = 0002;
        $errPack->info = "操作过程中断";
        $nc->close();
        die($errPack->parsePack());
    }

    $errPack->code = 0000;
    $errPack->info = "成功";
    $nc->close();
    die($errPack->parsePack());
}


// 判定为 消极游戏,每一次累加1
function passive($peopleid){
    $nc = new sqlihelper();
    $errPack = new errorpack();
    if(!$nc->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接错误";
        die($errPack->parsePack());
    }
    $result = $nc ->mysql("update question_people set active=active+1 where id='$peopleid'");
    if(!$result){
        $errPack->code = 0002;
        $errPack->info = "操作过程中断";
        $nc->close();
        die($errPack->parsePack());
    }

    $errPack->code = 0000;
    $errPack->info = "成功";
    $nc->close();
    die($errPack->parsePack());
}

//消除 消极游戏嫌疑
function clearactive($peopleid){
    $nc = new sqlihelper();
    $errPack = new errorpack();
    if(!$nc->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接错误";
        die($errPack->parsePack());
    }
    $result = $nc ->mysql("update question_people set active=0 where id='$peopleid'");
    if(!$result){
        $errPack->code = 0002;
        $errPack->info = "操作过程中断";
        $nc->close();
        die($errPack->parsePack());
    }

    $errPack->code = 0000;
    $errPack->info = "成功";
    $nc->close();
    die($errPack->parsePack());
}

//根据名字和联系方式查找并返回用户id
function findprofile($name,$tel){
    $nc = new sqlihelper();
    $errPack = new errorpack();
    if(!$nc->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接错误";
        die($errPack->parsePack());
    }
    $result = $nc ->mysql("select id from question_people where name='$name' and tel = '$tel' limit 1");
    if(!$result){
        $errPack->code = 0002;
        $errPack->info = "操作过程中断";
        $nc->close();
        die($errPack->parsePack());
    }
    if($result->num_rows==0){
        $errPack->code = 0002;
        $errPack->info = "找不到这个人的资料";
        $nc->close();
        die($errPack->parsePack());
    }

    $row = $result->fetch_assoc();
    $responsePack = new stdClass();
    $responsePack->{'code'} = 0000;
    $responsePack->{'id'} = $row['id'];
    $responsePack->{'info'} = "成功";
    $nc->close();
    die(json_encode($responsePack,JSON_UNESCAPED_UNICODE));
}


?>