<?php
/**
 * User: trickyEdecay
 * Date: 2017/4/26
 * Time: 21:14
 */
require_once ("checkString.php");
require_once ("encrypt.php");

if(!isset($_POST['what'])){
    $what = "";
}else{
    $what = $_POST['what'];
}
switch ($what){
    case 'login':
        login($_POST['name'],$_POST['tel']);
        break;
    case 'register':
        register($_POST['name'],$_POST['tel']);
        break;
}

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
    return $errPack->parsePack();
}

//检查登录状态
function getLoginState(){
    if(!session_id()) session_start();
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
        return $errPack->parsePack();
    }else{
        $db_player = new player();
        if(!$db_player->connect()){
            $errPack->code = 0001;
            $errPack->info = "居然连接失败了~请刷新页面重试";
            return $errPack->parsePack();
        }

        $row = $db_player->getInfo($_SESSION['peopleid'],"`randomkey`,`isbanned`");
        $randomKey = $row['randomkey'];
        $isBanned = $row['isbanned'];

        if($randomKey != $_SESSION['randomkey']){
            $errPack->code = 0002;
            $errPack->info = "你的账号在别的地方登录了哦~刷新重试";
            $db_player->close();
            return $errPack->parsePack();
        }

        if($isBanned !=0){
            $errPack->code = 0003;
            $errPack->info = "你的账号已经被封禁!请与工作人员联系!";
            $db_player->close();
            return $errPack->parsePack();
        }
    }

    $db_player->close();
    $errPack->code = 0000;
    $errPack->info = "验证成功";
    return $errPack->parsePack();
}

//登录
function login($name,$tel){

    $errPack = new errorpack();

    //检查前端传过来的数据
    if(empty($name)){
        $errPack->code = 0001;
        $errPack->info = "姓名不能为空";
        die($errPack->parsePack());
    }
    if(empty($tel)){
        $errPack->code = 0002;
        $errPack->info = "联系方式不能为空";
        die($errPack->parsePack());
    }
    if(!is_name($name)){
        $errPack->code = 0003;
        $errPack->info = "姓名的格式不正确";
        die($errPack->parsePack());
    }
    if(!is_longtel($tel) && !is_shorttel($tel)){
        $errPack->code = 0004;
        $errPack->info = "联系方式的格式不正确噢";
        die($errPack->parsePack());
    }

    $db_player = new player();
    if(!$db_player->connect()){
        $errPack->code = 0005;
        $errPack->info = "连接服务器出错啦，请检查您的网络";
        die($errPack->parsePack());
    }

    $name = $db_player->getMysqli()->real_escape_string($name);
    $tel = $db_player->getMysqli()->real_escape_string($tel);

    //判断用户是否已经存在于数据库中
    if(!$db_player->isPlayerExist($name,$tel)){
        $errPack->code = 0006;
        $errPack->info = "你的姓名或联系方式与你第一次登录时填写的数据不一样噢~";
        $db_player->close();
        die($errPack->parsePack());
    }

    $row = $db_player->result->fetch_assoc();
    $lastActiveYears = $row['lastactiveyear'];
    $peopleId = $row['id'];
    $year = YEAR;

    $now = date("Y-m-d H:i:s");
    $ts =  floor(microtime()*1000);

    //之前参加过比赛，year的值会和今年年份不一样
    if($lastActiveYears != $year){
        //记录之前的游戏数据
        $db_historyData = new HistoryData($db_player->getMysqli());
        $rightCount = $row['rightcount'];
        $wrongCount = $row['wrongcount'];
        $score = $row['score'];
        $ranking = $row['ranking'];

        //保存历史数据
        $db_historyData->pushPlayerHistoryData($peopleId,$rightCount,$wrongCount,$score,$ranking,$lastActiveYears);

        //因为往年参加过，所以就会有存留的数据，这些值必须被重置
        $db_player->resetPlayer($name,$tel,$now,$ts);
    }

    //刷新身份信息，每次登录都会生成一个 randomKey ，用来防止多处登录。
    $randomKey=uniqid();
    $db_player->refreshRandomKey($name,$tel,$randomKey);
    $db_player->close();

    //设置 cookie
    $id=$row['id'];
    $name=$row['name'];
    $timeout = time()+3600*24*2; //cookie保留两天
    setcookie('auth', "$id:$name:$randomKey", $timeout);

    session_start();
    $_SESSION['peopleid']=$id; //使用randomkey来记录登录状态
    $_SESSION['randomkey']=$randomKey; //使用randomkey来记录登录状态
    $_SESSION['name']=$name; //使用name来记录登录状态

    $errPack->code = 0000;
    $errPack->info = "登录成功!";
    die($errPack->parsePack());
}












//注册
function register($name,$tel){

    $errPack = new errorpack();

    $db_questionConfig = new questionConfig();
    if(!$db_questionConfig->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接服务器出错啦，请检查您的网络";
        die($errPack->parsePack());
    }

    //检查是不是已经关闭了注册
    if($db_questionConfig->getIsReging() != "true"){
        $errPack->code = 0002;
        $errPack->info = "请刷新页面重试~";
        $db_questionConfig->close();
        die($errPack->parsePack());
    }

    //检查前端传过来的数据
    if(empty($name)){
        $errPack->code = 0003;
        $errPack->info = "姓名不能为空";
        $db_questionConfig->close();
        die($errPack->parsePack());
    }
    if(empty($tel)){
        $errPack->code = 0004;
        $errPack->info = "联系方式不能为空";
        $db_questionConfig->close();
        die($errPack->parsePack());
    }
    if(!is_name($name)){
        $errPack->code = 0005;
        $errPack->info = "姓名的格式不正确";
        $db_questionConfig->close();
        die($errPack->parsePack());
    }
    if(!is_longtel($tel) && !is_shorttel($tel)){
        $errPack->code = 0006;
        $errPack->info = "联系方式的格式不正确噢";
        $db_questionConfig->close();
        die($errPack->parsePack());
    }

    $name = $db_questionConfig->getMysqli()->real_escape_string($name);
    $tel = $db_questionConfig->getMysqli()->real_escape_string($tel);

    $db_player = new player($db_questionConfig->getMysqli());

    //判断用户是否已经存在于数据库中
    if($db_player->isPlayerExist($name,$tel)){
        //存在就直接登录
        login($name,$tel);

        $errPack->code = 0000;
        $errPack->info = "登录成功~";
        $db_player->close();
        die($errPack->parsePack());
    }else{
        if($db_player->isTelExist($tel)){
            $errPack->code = 0007;
            $errPack->info = "此号码已经被注册过，请重试或联系工作人员";
            $db_player->close();
            die($errPack->parsePack());
        }
        //不存在，注册
        $now = date("Y-m-d H:i:s");
        $ts =  floor(microtime()*1000);

        $db_player->createNewPlayer($name,$tel,$now,$ts);
        if($db_player->getMysqli()->errno){
            $errPack->code = 0007;
            $errPack->info = "登记时出现了错误,请重试或联系科创社工作人员";
            $db_questionConfig->close();
            die($errPack->parsePack());
        }

        //然后登录
        login($name,$tel);

        $errPack->code = 0000;
        $errPack->info = "登录成功~";
        $db_player->close();
        die($errPack->parsePack());


    }
}