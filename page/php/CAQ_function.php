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
    case 'getAdminInfoPack':
        getAdminInfoPack();
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
    case 'whetheroldcomer':
        whetheroldcomer();
        break;
}
?>













<?php
//检查登录状态
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
//提交新的题目
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

//获取修改的题目信息
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
//删除某个题目
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





//切换题目至
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


//显示题目
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

//显示答案
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
    $year = date("Y");
    
    //获取题目概要
    $result = $nc ->mysql("select value from question_config where keyname = 'questionpack' limit 1");
    $row = mysql_fetch_array($result);
    $questionpack = json_decode($row['value']);
    $questionid = $questionpack->{'currentquestionid'};
    $addscore = $questionpack->{'addscore'};
    $minusscore = $questionpack->{'minusscore'};
    $randomtrue = $questionpack->{'correctanswer'};
    $peoplelimit = $questionpack->{'peoplelimit'};
    
    //答对加分
    $result = $nc ->mysql("update question_people,question_buffer 
    set question_people.score = question_people.score+$addscore,
    question_people.rightcount=question_people.rightcount+1,
    question_people.rightids=concat(IFNULL(question_people.rightids,''),'$questionid,'),
    achievetime = question_buffer.time, achievets = question_buffer.ts
    where 
    question_people.id = question_buffer.peopleid and 
    question_buffer.state='done' and 
    question_buffer.choose = '$randomtrue' and 
    question_buffer.questionid = $questionid");
    
    //答错扣分
    $result = $nc ->mysql("update question_people,question_buffer 
    set question_people.score = question_people.score-$minusscore,
    question_people.wrongcount=question_people.wrongcount+1,
    question_people.wrongids=concat(IFNULL(question_people.wrongids,''),'$questionid,'),
    achievetime = question_buffer.time, achievets = question_buffer.ts
    where 
    question_people.id = question_buffer.peopleid and 
    question_buffer.questionid = $questionid and
    (
    (question_buffer.state = 'done' and question_buffer.choose <> '$randomtrue') or
    question_buffer.state='joined' or
    question_buffer.state='timeout' or
    question_buffer.choose is null
    )");
    
    
    //如果有连续两次没有输入验证码,那么就会被扣除分数,扣除的分数是 连续没有参加的次数*2
    $result = $nc ->mysql("update question_people set score=score-active*2,activeminusscore=activeminusscore+active*2 where active>=2 and lastactiveyear='$year'");
    $result = $nc ->mysql("update question_people set active=active+1 where lastactiveyear='$year'");
    
    //重置那些分数被扣到负数的
    $result = $nc ->mysql("update question_people set score=0 where score<0");
    
    
    
    //计算排名
    $result = $nc->mysql("set @ranking=0");
    $result = $nc->mysql("update (select id,ranking,@ranking:=@ranking+1 as temprank from question_people where lastactiveyear='$year' order by score desc,achievetime asc,achievets asc) temp,question_people set question_people.ranking = temp.temprank ,question_people.oldranking = temp.ranking where question_people.id = temp.id");

    
    //记录历史分数和历史排名
    $result = $nc ->mysql("update question_people set historyscore=concat(IFNULL(historyscore,''),concat(score,',')) ,historyranking=concat(IFNULL(historyranking,''),concat(ranking,',')) where lastactiveyear='$year'");
    
    
    //数据库中预置排行榜
    $result = $nc ->mysql("select score,name,oldranking from question_people where isbanned=0 and lastactiveyear='$year' order by ranking asc limit 15");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    $index = 0;
    
    while($row = mysql_fetch_array($result)){
        $rankinglistpack->{'people'}[$index]->{'name'} = $row['name'];
        $rankinglistpack->{'people'}[$index]->{'score'} = $row['score'];
        $rankinglistpack->{'people'}[$index]->{'oldranking'} = $row['oldranking'];
        $index++;
    }
    
    $en_rankinglistpack = json_encode($rankinglistpack,JSON_UNESCAPED_UNICODE); //需要php5.4支持
    
    $result = $nc ->mysql("update question_config set value='$en_rankinglistpack' where keyname='rankinglistpack'");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    
    
    
    
    //计算答题延迟
    $result = $nc ->mysql("select time from question_buffer where questionid='$questionid' order by time desc limit 1");
    $row = mysql_fetch_array($result);
    $fulltime = $row['time'];
    $result = $nc ->mysql("update question_idcinputtime set delay=unix_timestamp(idcinputtime)-unix_timestamp('$fulltime') where questionid = '$questionid'");
    
    
    $err->{'code'} = 0000;
    $err->{'info'} = "操作成功";
    die(json_encode($err));
}


//显示赞助商
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
    
    //生成验证码
    srand((double)microtime()*1000000);
    $idc = rand(100000,999999);
    $result = $nc ->mysql("update question_config set value='$idc' where keyname='idc'");
    
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    
    $year = date("Y");
    $result = $nc ->mysql("select * from question where id = '$questionid' limit 1");
    $row = mysql_fetch_array($result);
    $peoplelimit = $row['peoplelimit'];
    $sort = $row['sort'];
    $addscore = $row['addscore'];
    $minusscore = $row['minusscore'];
    $answera = addslashes($row['a']);
    $answerb = addslashes($row['b']);
    $answerc = addslashes($row['c']);
    $answerd = addslashes($row['d']);
    $correctanswer = $row['randomtrue'];
    $question = addslashes($row['question']);
    $availabletime = $row['availabletime'];
    
    
    
    
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    
    //先在数据库中预置问题
    $questionpack->{'currentquestionid'} = $questionid;
    $questionpack->{'peoplelimit'} = $peoplelimit;
    $questionpack->{'sort'} = $sort;
    $questionpack->{'addscore'} = $addscore;
    $questionpack->{'minusscore'} = $minusscore;
    $questionpack->{'availabletime'} = $availabletime;
    $questionpack->{'answera'} = $answera;
    $questionpack->{'answerb'} = $answerb;
    $questionpack->{'answerc'} = $answerc;
    $questionpack->{'answerd'} = $answerd;
    $questionpack->{'correctanswer'} = $correctanswer;
    $questionpack->{'question'} = $question;
    
    $en_questionpack = json_encode($questionpack,JSON_UNESCAPED_UNICODE); //需要php5.4支持
    
    $result = $nc ->mysql("update question_config set value='$en_questionpack' where keyname='questionpack'");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    
    
    //数据库中预置排行榜
    $result = $nc ->mysql("select score,name,oldranking from question_people where isbanned=0 and lastactiveyear='$year' order by ranking limit 15");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    $index = 0;
    
    while($row = mysql_fetch_array($result)){
        $rankinglistpack->{'people'}[$index]->{'name'} = $row['name'];
        $rankinglistpack->{'people'}[$index]->{'score'} = $row['score'];
        $rankinglistpack->{'people'}[$index]->{'oldranking'} = $row['oldranking'];
        $index++;
    }
    
    $en_rankinglistpack = json_encode($rankinglistpack,JSON_UNESCAPED_UNICODE); //需要php5.4支持
    
    $result = $nc ->mysql("update question_config set value='$en_rankinglistpack' where keyname='rankinglistpack'");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    
    
    
    $err->{'code'} = 0000;
    $err->{'info'} = "操作成功";
    die(json_encode($err));
}


//显示最终排行榜
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


//开启关闭注册
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

//获取后台信息
function getAdminInfoPack(){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("select value from question_config where keyname = 'questionpack' limit 1");
    $row = mysql_fetch_array($result);
    $questionpack = json_decode($row['value']);
    $cid = $questionpack->{'currentquestionid'};
    
    $result = $nc ->mysql("select value from question_config where keyname = 'answerstate' limit 1");
    $row = mysql_fetch_array($result);
    $questionstate = $row['value'];

    $peoplelimit = $questionpack->{'peoplelimit'};
    $question = $questionpack->{'question'};
    $sort = $questionpack->{'sort'};
    $addscore = $questionpack->{'addscore'};
    $minusscore = $questionpack->{'minusscore'};
    
    $result = $nc ->mysql("select id from question_buffer where questionid = '$cid' and state = 'joined' limit $peoplelimit");
    $peopleJoined = mysql_num_rows($result);
    
    $result = $nc ->mysql("select id from question_buffer where questionid = '$cid' and state = 'done' limit $peoplelimit");
    $peopleDone = mysql_num_rows($result);
    
    $result = $nc ->mysql("select id from question_buffer where questionid = '$cid' and state = 'timeout' limit $peoplelimit");
    $peopleTimeout = mysql_num_rows($result);
    
    
    $err->{'code'} = 0000;
    $err->{'info'} = "操作成功";
    $err->{'questionstate'} = $questionstate;
    $err->{'currentquestionid'} = $cid;
    $err->{'peoplelimit'} = $peoplelimit;
    $err->{'sort'} = $sort;
    $err->{'question'} = $question;
    $err->{'addscore'} = $addscore;
    $err->{'minusscore'} = $minusscore;
    $err->{'peoplejoined'} = $peopleJoined;
    $err->{'peopledone'} = $peopleDone;
    $err->{'peopletimeout'} = $peopleTimeout;
    die(json_encode($err));
}


//获取问题信息
function getQuestionInfo(){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    $result = $nc ->mysql("select value from question_config where keyname = 'questionpack' limit 1");
    $row = mysql_fetch_array($result);
    $questionpack = json_decode($row['value']);

    $peoplelimit = $questionpack->{'peoplelimit'};
    $sort = $questionpack->{'sort'};
    $addscore = $questionpack->{'addscore'};
    $minusscore = $questionpack->{'minusscore'};
    
    $result = $nc ->mysql("select id from question_buffer where questionid = '$cid' and state = 'joined' limit $peoplelimit");
    $peopleJoined = mysql_num_rows($result);
    
    $result = $nc ->mysql("select id from question_buffer where questionid = '$cid' and (state = 'done' or state = 'timeout') limit $peoplelimit");
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
    
    $result = $nc->mysql("select * from question_people where name='$name' and tel='$tel' limit 1");
    if(mysql_num_rows($result)==0){
        $err->{'code'} = 0003;
        $err->{'info'} = "你的姓名或联系方式与你第一次登录时填写的数据不一样噢~";
        die(json_encode($err));
    }
    $row = mysql_fetch_array($result);
    
    //判断是否已经添加今年的年份到数据库中,如果活动改为上学期办的话,这里可能需要修改
    $participateyears = $row['participateyears'];
    $historydata = $row['historydata'];
    $peopleid = $row['id'];
    $year = date("Y");
    
    $ifoldcomersql = ""; //如果进行下面这个if的话才会有的sql语句
    if(stripos($participateyears,$year)===false){
        $participateyears = $participateyears.",".$year; //添加今年的年份
        //接下来把历史的资料放到数据库中
        $historydata = json_decode($historydata);
        $years = explode(",",$participateyears);
        $lastyear = $years[(count($years)-2)];
        $historydata->{$lastyear}->{'rightcount'}=$row['rightcount'];
        $historydata->{$lastyear}->{'wrongcount'}=$row['wrongcount'];
        $historydata->{$lastyear}->{'score'}=$row['score'];
        $historydata->{$lastyear}->{'ranking'}=$row['ranking'];
        $historydata = json_encode($historydata);
        
        $now = date("Y-m-d H:i:s");
        $ts =  floor(microtime()*1000);
        
        
        //作为之前来过的玩家需要重置他们数据库里面的资料
        $ifoldcomersql = ",participateyears = '$participateyears',historydata='$historydata',rightcount='0',wrongcount='0',score='0',ranking='66',oldranking='66',achievetime='$now',achievets='$ts',wrongidccount='0',isbanned='0',rightids='',wrongids='',active='1',activeminusscore='0',lastactiveyear='$year',historyscore='0',historyranking='66'";
    }
    
    $randomkey=uniqid();
    $result = $nc->mysql("update question_people set randomkey='$randomkey' $ifoldcomersql where name='$name' and tel='$tel'");
    
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



//注册
function register($name,$tel){
    
    $nc = new sqlhelper;
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0002;
        $err->{'info'} = "连接服务器出错啦";
        die(json_encode($err));
    }
    
    $result = $nc->mysql("select * from question_config where keyname='isreging' limit 1");
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
    
    
    
    $result = $nc->mysql("select id,name from question_people where name='$name' and tel='$tel' limit 1");
    if(mysql_num_rows($result)!=0){
        //账号已经存在的情况
        
        $row = mysql_fetch_array($result);
        
        
        
        login($name,$tel);
        $err->{'code'} = 0000;
        $err->{'info'} = "登录成功";
        die(json_encode($err));
    }
    
    //账号不存在的情况
    $now = date("Y-m-d H:i:s");
    $year = date("Y");
    $ts =  floor(microtime()*1000);
    $result = $nc->mysql("insert into question_people (name,tel,achievetime,achievets,participateyears,lastactiveyear,ranking,oldranking,historyscore,historyranking) values('$name','$tel','$now','$ts','$year','$year',66,66,0,66)");
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


//检查验证码对不对
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
        $result = $nc->mysql("update question_people set isbanned=1 where wrongidccount>=3 and id='$peopleid' limit 1");
        
        $err->{'code'} = 0003;
        $err->{'info'} = "验证码输入错误!输错3次将被封号!";
        die(json_encode($err));
    }
    
    $now = date("Y-m-d H:i:s");
    $ts =  floor(microtime()*1000);
    
    //输入验证码了就可以去除掉你的连续没答题记录
    $result = $nc ->mysql("update question_people set active=0 where id='$peopleid' limit 1");
    
    $result = $nc->mysql("select value from question_config where keyname='questionpack' limit 1");
    $row = mysql_fetch_array($result);
    $questionpack= json_decode($row['value']);
    $currentquestionid = $questionpack ->{'currentquestionid'};
    $peoplelimit = $questionpack ->{'peoplelimit'};
    
    //记录输入验证码的时间
    $result = $nc ->mysql("select peopleid,questionid from question_idcinputtime where peopleid='$peopleid' and questionid='$currentquestionid' limit 1");
    if(mysql_num_rows($result)<=0){
        $result = $nc ->mysql("insert into question_idcinputtime (peopleid,questionid,idcinputtime) values ('$peopleid','$currentquestionid','$now')");
    }
    
    //下面的limit没用错,一旦人数等于limit的人数就意味着满了
    $result = $nc->mysql("select peopleid from question_buffer where questionid='$currentquestionid' limit $peoplelimit");
    if(mysql_num_rows($result)>=$peoplelimit){
        $err->{'code'} = 0004;
        $err->{'info'} = "现在已经达到了该题目的限制人数咯~不能再进了噢~";
        die(json_encode($err));
    }
    
    //检查这个人是否已经存在于buffer中
    $result = $nc->mysql("select id,state,choose from question_buffer where questionid='$currentquestionid' and peopleid='$peopleid' limit 1");
    if(mysql_num_rows($result)<=0){
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
    
    //二次验证人数是不是已经到达上限,因为有可能在上面这个验证过程中出现人数满了的情况
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













//提交答案
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
    
    
    //提交的题号不是当前题号id
    $result = $nc->mysql("select value from question_config where keyname='currentquestionid' limit 1");
    $row = mysql_fetch_array($result);
    $currentquestionid = $row['value'];
    if($currentquestionid != $questionid){
        $err->{'code'} = 0003;
        $err->{'info'} = "<script>scanstate('infopage','err::你没有正确地进入到题目中来噢');</script>";
        die(json_encode($err));
    }
    
    //还不是答题状态
    $result = $nc->mysql("select value from question_config where keyname='isanswering' limit 1");
    $row = mysql_fetch_array($result);
    if($row['value']=="false"){
        $err->{'code'} = 0004;
        $err->{'info'} = "答题还没开始进行!请注视大屏幕~";
        die(json_encode($err));
    }
    
    session_start();
    $peopleid = $_SESSION['peopleid'];
    
    
    //在buffer中找不到数据
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















//提交 答案超时
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














//判断是不是老玩家
function whetheroldcomer(){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        $err->{'code'} = 0001;
        $err->{'info'} = "连接错误";
        die(json_encode($err));
    }
    
    session_start();
    $peopleid = $_SESSION['peopleid'];
    
    $result = $nc ->mysql("select participateyears from question_people where id = $peopleid limit 1");
    if(!$result){
        $err->{'code'} = 0002;
        $err->{'info'} = "操作中断了,请重试";
        die(json_encode($err));
    }
    $row = mysql_fetch_array($result);
    if(strlen($row['participateyears'])<=5){
        //是新玩家
        $err->{'code'} = 0000;
        $err->{'info'} = "是新玩家";
        $err->{'isoldcomer'} = "false";
        die(json_encode($err));
    }
    
    $err->{'code'} = 0000;
    $err->{'info'} = "操作成功";
    $err->{'isoldcomer'} = "true";
    die(json_encode($err));
}


?>