<?php //用来处理知识问答竞赛?>
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
    case 'submit':
        submit(
            $_POST['question'],
            $_POST['a'],
            $_POST['b'],
            $_POST['c'],
            $_POST['d'],
                                
            $_POST['peoplelimit'],
            $_POST['addscore'],
            $_POST['minusscore'],
            $_POST['sort'],
            $_POST['availabletime'],
            $_POST['ope'],
                                
            $_POST['id']
        );
        break;
    case 'getModifyInfo':
        getModifyInfo($_POST['id']);
        break;
    case 'deleteid':
        deleteid($_POST['id']);
        break;
    case 'login': 
        login($_POST['name'],$_POST['tel']);
        break;
    case 'register': 
        register($_POST['name'],$_POST['tel']);
        break;
    case 'changequestionto':
        changequestionto($_POST['questionid']);
        break;
    case 'showquestion':
        showquestion($_POST['questionid']);
        break;
    case 'showkey':
        showkey($_POST['questionid']);
        break;
    case 'showsponser':
        showsponser($_POST['questionid']);
        break;
    case 'showprize':
        showprize();
        break;
    case 'getQuestionInfo':
        getQuestionInfo();
        break;
    case 'togglereging':
        togglereging($_POST['changeto']);
        break;
    //用户提交
    case 'checkidc':
        checkidc($_POST['idc']);
        break;
    case 'submitanswer':
        submitanswer($_POST['choose'],$_POST['questionid']);
        break;
    case 'submitanswertimeout':
        submitanswertimeout($_POST['questionid']);
        break;
}
?>













<?php
function checkCAQlogin(){
    session_start();
    if(isset($_COOKIE['auth'])){ //先判断cookie存在与否
        list($peopleid, $name,$randomkey) = explode(':', $_COOKIE['auth']);
        $_SESSION['randomkey']=$randomkey; //使用randomkey来记录登录状态
        $_SESSION['name']=$name; //使用name来记录登录状态
        $_SESSION['peopleid']=$peopleid; //使用name来记录登录状态
    }


    if(!isset($_SESSION['randomkey'])){
        $err->{'code'} = 0001;
        $err->{'info'} = "你的登录信息已经过期~请重新登录";
        return $err;
    }else{
        $nc = new sqlhelper;
        $opecode = $nc->connect();
        if($opecode!=0){
            $err->{'code'} = 0002;
            $err->{'info'} = "居然连接失败了~请刷新页面重试";
            return $err;
        }
        $result = $nc->mysql("select randomkey from question_people where id='{$_SESSION['peopleid']}' limit 1");
        $row = mysql_fetch_array($result);
        if($row['randomkey'] != $_SESSION['randomkey']){
            $err->{'code'} = 0003;
            $err->{'info'} = "你的账号在别的地方登录了哦~刷新重试";
            return $err;
        }
        if($row['isbanned'] !=0){
            $err->{'code'} = 0004;
            $err->{'info'} = "你的账号已经被封禁!请与工作人员联系!";
            return $err;
        }
    }
    
    $err->{'code'} = 0000;
    $err->{'info'} = "验证成功";
    return $err;
}










//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

function submit($question,$a,$b,$c,$d,$peoplelimit,$addscore,$minusscore,$sort,$availabletime,$ope,$index){
    
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    
    $randomtrue = chr(65+rand(0,3));
    
    if($ope == "add"){
    $result = $nc ->mysql("insert into question(question,a,b,c,d,randomtrue,peoplelimit,addscore,minusscore,sort,availabletime) values
                                        ('$question','$a','$b','$c','$d','$randomtrue','$peoplelimit','$addscore','$minusscore','$sort','$availabletime')");
        $err->{'code'} = 0000;
        $err->{'info'} = "成功";
        die(json_encode($err));
    }elseif($ope == "edit"){
        $result = $nc ->mysql("update question set question = '$question',a ='$a',b='$b',c='$c',d='$d',peoplelimit='$peoplelimit',addscore ='$addscore',minusscore='$minusscore',sort='$sort',availabletime='$availabletime' where id='$index'");
        $err->{'code'} = 0000;
        $err->{'info'} = "成功";
        die(json_encode($err));
    }
}


function getModifyInfo($id){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("select * from question where id = $id limit 1");
    $row = mysql_fetch_array($result);
    $modifyinfo->{'code'} = 0000;
    $modifyinfo->{'id'} = $row['id'];
    $modifyinfo->{'question'} = $row['question'];
    $modifyinfo->{'a'} = $row['a'];
    $modifyinfo->{'b'} = $row['b'];
    $modifyinfo->{'c'} = $row['c'];
    $modifyinfo->{'d'} = $row['d'];
    $modifyinfo->{'peoplelimit'} = $row['peoplelimit'];
    $modifyinfo->{'addscore'} = $row['addscore'];
    $modifyinfo->{'minusscore'} = $row['minusscore'];
    $modifyinfo->{'sort'} = $row['sort'];
    $modifyinfo->{'availabletime'} = $row['availabletime'];
    die(json_encode($modifyinfo));
}

function deleteid($id){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("delete from question where id=$id");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "删除错误";
        die(json_encode($err));
    }
    
    $err->{'code'} = 0000;
    $err->{'info'} = "成功";
    die(json_encode($err));
}


function changequestionto($questionid){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("update question_config set value='true' where keyname='isanswering'");
    $result = $nc ->mysql("update question_config set value='idc' where keyname='answerstate'");
    
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    
    $err->{'code'} = 0000;
    $err->{'info'} = "操作成功";
    die(json_encode($err));
}

function showquestion($questionid){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("update question_config set value='showingquestion' where keyname='answerstate'");
    $result = $nc ->mysql("update question_config set value='true' where keyname='isanswering'");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    
    $err->{'code'} = 0000;
    $err->{'info'} = "操作成功";
    die(json_encode($err));
}


function showkey($questionid){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("update question_config set value='showingkey' where keyname='answerstate'");
    $result = $nc ->mysql("update question_config set value='false' where keyname='isanswering'");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    $now = date("Y-m-d H:i:s");
    
    //要用毫秒来记录先后顺序
    $ts =  floor(microtime()*1000);
    
    $result = $nc ->mysql("select id,addscore,minusscore,randomtrue from question where question.id = (select value from question_config where keyname = 'currentquestionid') limit 1");
    $row = mysql_fetch_array($result);
    $questionid = $row['id'];
    $addscore = $row['addscore'];
    $minusscore = $row['minusscore'];
    $randomtrue = $row['randomtrue'];
    $result = $nc ->mysql("update question_people,question_buffer set question_people.score = question_people.score+$addscore,question_people.rightcount=question_people.rightcount+1,question_people.rightids=concat(rightids,'$questionid,'),achievetime = '$now',achievets = '$ts' where question_people.id = question_buffer.peopleid and question_buffer.state='done' and question_buffer.choose = '$randomtrue' and question_buffer.questionid = $questionid");
    $result = $nc ->mysql("update question_people,question_buffer set question_people.score = question_people.score-$minusscore,question_people.wrongcount=question_people.wrongcount+1,question_people.wrongids=concat(wrongids,'$questionid,'),achievetime = '$now',achievets = '$ts' where question_people.id = question_buffer.peopleid and (question_buffer.state='done' or question_buffer.state='joined' or question_buffer.state='timeout') and question_buffer.choose <> '$randomtrue' and question_buffer.questionid = $questionid");
    $result = $nc ->mysql("update question_people set score=0 where score<0");
    
    $result = $nc->mysql("set @ranking=0");
    $result = $nc->mysql("update (select id,@ranking:=@ranking+1 as temprank from question_people order by score desc,achievetime asc,achievets asc) temp,question_people set question_people.ranking = temp.temprank where question_people.id = temp.id");

    
    $err->{'code'} = 0000;
    $err->{'info'} = "操作成功";
    die(json_encode($err));
}

function showsponser($questionid){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("update question_config set value='$questionid' where keyname='currentquestionid'");
    $result = $nc ->mysql("update question_config set value='false' where keyname='isanswering'");
    $result = $nc ->mysql("update question_config set value='showingsponser' where keyname='answerstate'");
    $now = date("Y-m-d H:i:s");
    $result = $nc ->mysql("update question_config set value='$now' where keyname='questionopentime'");
    srand((double)microtime()*1000000);
    $idc = rand(100000,999999);
    $result = $nc ->mysql("update question_config set value='$idc' where keyname='idc'");
    
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    
    $err->{'code'} = 0000;
    $err->{'info'} = "操作成功";
    die(json_encode($err));
}

function showprize(){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("update question_config set value='showingprize' where keyname='answerstate'");
    $result = $nc ->mysql("update question_config set value='false' where keyname='isanswering'");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    
    $err->{'code'} = 0000;
    $err->{'info'} = "操作成功";
    die(json_encode($err));
}



function togglereging($changeto){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("update question_config set value='$changeto' where keyname='isreging'");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    
    $err->{'code'} = 0000;
    $err->{'info'} = "操作成功";
    die(json_encode($err));
}

function getQuestionInfo(){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("select * from question_config where keyname = 'currentquestionid' limit 1");
    $row = mysql_fetch_array($result);
    $cid = $row['value'];
    
    $result = $nc ->mysql("select * from question where id = '$cid' limit 1");
    $row = mysql_fetch_array($result);
    $peoplelimit = $row['peoplelimit'];
    $sort = $row['sort'];
    $addscore = $row['addscore'];
    $minusscore = $row['minusscore'];
    
    $result = $nc ->mysql("select * from question_buffer where questionid = '$cid' and state = 'joined' limit $peoplelimit");
    $peopleJoined = mysql_num_rows($result);
    
    $result = $nc ->mysql("select * from question_buffer where questionid = '$cid' and (state = 'done' or state = 'timeout') limit $peoplelimit");
    $peopleDone = mysql_num_rows($result);
    
    
    $err->{'code'} = 0000;
    $err->{'info'} = "操作成功";
    $err->{'currentquestionid'} = $cid;
    $err->{'peoplelimit'} = $peoplelimit;
    $err->{'sort'} = $sort;
    $err->{'addscore'} = $addscore;
    $err->{'minusscore'} = $minusscore;
    $err->{'peoplejoined'} = $peopleJoined;
    $err->{'peopledone'} = $peopleDone;
    die(json_encode($err));
}
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
















//登录
function login($name,$tel){
    if(empty($name)){
        $err->{'code'} = 0001;
        $err->{'info'} = "姓名不能为空";
        die(json_encode($err));
    }
    if(empty($tel)){
        $err->{'code'} = 0001;
        $err->{'info'} = "联系方式不能为空";
        die(json_encode($err));
    }
    if(!is_name($name)){
        $err->{'code'} = 0001;
        $err->{'info'} = "姓名的格式不正确";
        die(json_encode($err));
    }
    if(!is_longtel($tel) && !is_shorttel($tel)){
        $err->{'code'} = 0001;
        $err->{'info'} = "联系方式的格式不正确噢";
        die(json_encode($err));
    }
    
    $nc = new sqlhelper;
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0002;
        $err->{'info'} = "连接服务器出错啦";
        die(json_encode($err));
    }
    $name= mysql_real_escape_string($name);
    $tel= mysql_real_escape_string($tel);
    
    $result = $nc->mysql("select * from question_people where name='$name'");
    if(mysql_num_rows($result)==0){
        $err->{'code'} = 0003;
        $err->{'info'} = "你的姓名或联系方式与你第一次登录时填写的数据不一样噢~";
        die(json_encode($err));
    }
    $row = mysql_fetch_array($result);
    if($tel!=$row['tel']){
        $err->{'code'} = 0003;
        $err->{'info'} = "你的姓名或联系方式与你第一次登录时填写的数据不一样噢~";
        die(json_encode($err));
    }
    $randomkey=uniqid();
    $result = $nc->mysql("update question_people set randomkey='$randomkey' where name='$name' and tel='$tel'");
    
    //设置cookie
    $id=$row['id'];
    $name=$row['name'];
    $timeout = time()+3600*24*2; //cookie保留两天
    setcookie('auth', "$id:$name:$randomkey", $timeout);
    
    session_start();
    $_SESSION['peopleid']=$id; //使用randomkey来记录登录状态
    $_SESSION['randomkey']=$randomkey; //使用randomkey来记录登录状态
    $_SESSION['name']=$name; //使用name来记录登录状态
    
    $err->{'code'} = 0000;
    $err->{'info'} = "登录成功!";
    die(json_encode($err));
}




function register($name,$tel){
    
    $nc = new sqlhelper;
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0002;
        $err->{'info'} = "连接服务器出错啦";
        die(json_encode($err));
    }
    
    $result = $nc->mysql("select * from question_config where keyname='isreging'");
    $row = mysql_fetch_array($result);
    if($row['value'] != 'true'){
        $err->{'code'} = 0001;
        $err->{'info'} = "请刷新页面重试~";
        die(json_encode($err));
    }
    
    if(empty($name)){
        $err->{'code'} = 0001;
        $err->{'info'} = "姓名不能为空";
        die(json_encode($err));
    }
    if(empty($tel)){
        $err->{'code'} = 0001;
        $err->{'info'} = "联系方式不能为空";
        die(json_encode($err));
    }
    if(!is_name($name)){
        $err->{'code'} = 0001;
        $err->{'info'} = "姓名的格式不正确";
        die(json_encode($err));
    }
    if(!is_longtel($tel) && !is_shorttel($tel)){
        $err->{'code'} = 0001;
        $err->{'info'} = "联系方式的格式不正确噢";
        die(json_encode($err));
    }
    
    
    $name= mysql_real_escape_string($name);
    $tel= mysql_real_escape_string($tel);
    
    $result = $nc->mysql("select * from question_people where name='$name' and tel='$tel'");
    if(mysql_num_rows($result)!=0){
        //账号已经存在的情况
        login($name,$tel);
        $err->{'code'} = 0000;
        $err->{'info'} = "登录成功";
        die(json_encode($err));
    }
    $now = date("Y-m-d H:i:s");
    $ts =  floor(microtime()*1000);
    $result = $nc->mysql("insert into question_people (name,tel,achievetime,achievets) values('$name','$tel','$now','$ts')");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "登记时出现了错误,请重试或联系科创社工作人员";
        die(json_encode($err));
    }
    login($name,$tel);
    $err->{'code'} = 0000;
    $err->{'info'} = "登录成功";
    die(json_encode($err));
}













//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------



function checkidc($idc){
    checkCAQlogin();
    
    if(empty($idc)){
        $err->{'code'} = 0001;
        $err->{'info'} = "验证码不能为空";
        die(json_encode($err));
    }
    
    $nc = new sqlhelper;
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0002;
        $err->{'info'} = "连接服务器出错啦";
        die(json_encode($err));
    }
    $result = $nc->mysql("select value from question_config where keyname='isanswering' limit 1");
    $row = mysql_fetch_array($result);
    if($row['value']=="false"){
        $err->{'code'} = 0003;
        $err->{'info'} = "答题还没开始进行!请注视大屏幕~";
        die(json_encode($err));
    }
    
    session_start();
    $peopleid = $_SESSION['peopleid'];
    
    $result = $nc->mysql("select value from question_config where keyname='idc' limit 1");
    $row = mysql_fetch_array($result);
    if($idc != $row['value']){
        $result = $nc->mysql("update question_people set wrongidccount=wrongidccount+1 where id='$peopleid' limit 1");
        $result = $nc->mysql("update question_people set isbanned=1 where wrongidccount>=3 limit 1");
        
        $err->{'code'} = 0003;
        $err->{'info'} = "验证码输入错误!输错3次将被封号!";
        die(json_encode($err));
    }
    
    
    $result = $nc->mysql("select value from question_config where keyname='currentquestionid' limit 1");
    $row = mysql_fetch_array($result);
    $currentquestionid = $row['value'];
    
    $result = $nc->mysql("select peoplelimit from question where id='$currentquestionid' limit 1");
    $row = mysql_fetch_array($result);
    $peoplelimit = $row['peoplelimit'];
    
    $result = $nc->mysql("select peopleid from question_buffer where questionid='$currentquestionid' limit $peoplelimit");
    if(mysql_num_rows($result)>=$peoplelimit){
        $err->{'code'} = 0004;
        $err->{'info'} = "现在已经达到了该题目的限制人数咯~不能再进了噢~";
        die(json_encode($err));
    }
    
    $result = $nc->mysql("select id,state,choose from question_buffer where questionid='$currentquestionid' and peopleid='$peopleid' limit 1");
    if(mysql_num_rows($result)<=0){
        $now = date("Y-m-d H:i:s");
        $ts =  floor(microtime()*1000);
        $result = $nc->mysql("insert into question_buffer(peopleid,questionid,time,state,ts) values ('$peopleid','$currentquestionid','$now','waiting','$ts')");
        if(!$result){
            $err->{'code'} = 0002;
            $err->{'info'} = "连接出错啦~重新试试吧".mysql_error();
            die(json_encode($err));
        }
        $result = $nc->mysql("select id from question_buffer where questionid='$currentquestionid' and peopleid='$peopleid' limit 1");
        $row = mysql_fetch_array($result);
    }else{
        $row = mysql_fetch_array($result);
        if($row['state']=="done" || $row['choose'] != ""){
            $err->{'code'} = 0003;
            $err->{'info'} = "<script>scanstate('infopage','success::你刚才已经提交过答案啦~');</script>";
            die(json_encode($err));
        }
        if($row['state']=="timeout"){
            $err->{'code'} = 0003;
            $err->{'info'} = "<script>scanstate('infopage','err::你刚才答题超时~不许再进了噢~');</script>";
            die(json_encode($err));
        }
        
    }
    
    
    $bufferid = $row['id'];
    
    $result = $nc->mysql("select id from question_buffer where questionid='$currentquestionid' and peopleid='$peopleid' and id<$bufferid");
    if(mysql_num_rows($result)>$peoplelimit){
        $err->{'code'} = 0004;
        $err->{'info'} = "现在已经达到了该题目的限制人数咯~不能再进了噢~";
        die(json_encode($err));
    }
    
    $result = $nc->mysql("update question_buffer set state='joined' where questionid='$currentquestionid' and peopleid='$peopleid' limit 1");

    $err->{'code'} = 0004;
    $err->{'info'} = "<script>scanstate('answer');</script>";
    die(json_encode($err));
}














function submitanswer($choose,$questionid){
    checkCAQlogin();
    
    if(empty($choose)){
        $err->{'code'} = 0001;
        $err->{'info'} = "选项不能为空";
        die(json_encode($err));
    }
    
    if($choose != 'A' && $choose != 'B' && $choose != 'C' && $choose != 'D'){
        $err->{'code'} = 0001;
        $err->{'info'} = "提交的数据有问题噢~";
        die(json_encode($err));
    }
    
    $nc = new sqlhelper;
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0002;
        $err->{'info'} = "连接服务器出错啦";
        die(json_encode($err));
    }
    
    $result = $nc->mysql("select value from question_config where keyname='currentquestionid' limit 1");
    $row = mysql_fetch_array($result);
    $currentquestionid = $row['value'];
    if($currentquestionid != $questionid){
        $err->{'code'} = 0003;
        $err->{'info'} = "<script>scanstate('infopage','err::你没有正确地进入到题目中来噢');</script>";
        die(json_encode($err));
    }
    
    $result = $nc->mysql("select value from question_config where keyname='isanswering' limit 1");
    $row = mysql_fetch_array($result);
    if($row['value']=="false"){
        $err->{'code'} = 0004;
        $err->{'info'} = "答题还没开始进行!请注视大屏幕~";
        die(json_encode($err));
    }
    
    session_start();
    $peopleid = $_SESSION['peopleid'];
    
    $result = $nc->mysql("select state from question_buffer where questionid='$currentquestionid' and peopleid='$peopleid' limit 1");
    if(mysql_num_rows($result)<=0){
        $err->{'code'} = 0003;
        $err->{'info'} = "<script>scanstate('infopage','err::你没有正确地进入到题目中来噢');</script>";
        die(json_encode($err));
    }
    $row = mysql_fetch_array($result);
    $state = $row['state'];
    if($state == "done"){
        $err->{'code'} = 0003;
        $err->{'info'} = "<script>scanstate('infopage','err::你没有正确地进入到题目中来噢');</script>";
        die(json_encode($err));
    }
    if($state == "waiting"){
        $err->{'code'} = 0003;
        $err->{'info'} = "<script>scanstate('infopage','err::你并没有抢到抢答权噢~');</script>";
        die(json_encode($err));
    }
    
    if($state == "joined"){
        $result = $nc->mysql("update question_buffer set state='done',choose='$choose' where questionid='$currentquestionid' and peopleid='$peopleid' limit 1");
        $err->{'code'} = 0000;
        $err->{'info'} = "<script>scanstate('infopage','success::提交答案成功~');</script>";
        die(json_encode($err));
    }
}
















function submitanswertimeout($questionid){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    
    session_start();
    $peopleid = $_SESSION['peopleid'];
    
    $result = $nc ->mysql("update question_buffer set state='timeout' where questionid='$questionid' and peopleid='$peopleid' limit 1");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    
    $err->{'code'} = 0000;
    $err->{'info'} = "操作成功";
    die(json_encode($err));
}






?>