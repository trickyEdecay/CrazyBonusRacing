<?php //用来处理知识问答竞赛部分后台的细节?>
<?php
require_once("../config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/checkString.php');

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
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("update question_people set isbanned=0,wrongidccount=0 where id='$peopleid'");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "错误";
        die(json_encode($err));
    }
    
    $err->{'code'} = 0000;
    $err->{'info'} = "成功";
    die(json_encode($err));
}

// 判定为 消极游戏,每一次累加1
function passive($peopleid){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("update question_people set active=active+1 where id='$peopleid'");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "错误";
        die(json_encode($err));
    }
    
    $err->{'code'} = 0000;
    $err->{'info'} = "成功";
    die(json_encode($err));
}

//消除 消极游戏嫌疑
function clearactive($peopleid){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("update question_people set active=0 where id='$peopleid'");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "错误";
        die(json_encode($err));
    }
    
    $err->{'code'} = 0000;
    $err->{'info'} = "成功";
    die(json_encode($err));
}

//根据名字和联系方式查找并返回用户id
function findprofile($name,$tel){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("select id from question_people where name='$name' and tel = '$tel' limit 1");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "错误";
        die(json_encode($err));
    }
    if(mysql_num_rows($result)==0){
        $err->{'code'} = 0003;
        $err->{'info'} = "找不到这个人的资料";
        die(json_encode($err));
    }
    
    $row = mysql_fetch_array($result);
    $err->{'code'} = 0000;
    $err->{'id'} = $row['id'];
    $err->{'info'} = "成功";
    die(json_encode($err));
}


?>