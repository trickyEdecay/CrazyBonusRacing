<?php
/**
 * User: trickyEdecay
 * Date: 2017/4/26
 * Time: 21:01
 */

require_once ("checkString.php");
require_once ("encrypt.php");
require_once ("login.php");

if(!isset($_POST['what'])){
    die();
}
$what = $_POST['what'];
switch ($what){
    case 'checkCaptcha':
        checkCaptcha($_POST['captcha']);
        break;
    case 'submitSolution':
        submitSolution($_POST['choose'],$_POST['questionId']);
        break;
    case 'iveBeenTimeout':
        iveBeenTimeout($_POST['questionId']);
        break;
    case 'isOldHand':
        isOldHand();
        break;
    case 'getProfile':
        getProfile();
        break;
    case 'getQuestionPack':
        getQuestionPack();
        break;
}



//检查验证码对不对
function checkCaptcha($captcha){
    checkCAQlogin();

    $errPack = new errorpack();
    if(empty($captcha)){
        $errPack->code = 0002;
        $errPack->info = "验证码不能为空";
        die($errPack->parsePack());
    }

    $db_questionConfig = new questionConfig();

    if(!$db_questionConfig->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }

    $db_player = new player($db_questionConfig->getMysqli());
    $db_questionBuffer = new questionBuffer($db_questionConfig->getMysqli());
    $db_captchaDelay = new CaptchaDelay($db_questionConfig->getMysqli());

    if(!$db_questionConfig->getIsAnswering()){
        $errPack->code = 0003;
        $errPack->info = "答题还没开始进行!请注视大屏幕~";
        $db_questionConfig->close();
        die($errPack->parsePack());
    }

    $peopleId = $_SESSION['peopleid'];


    //读取验证码
    $realCaptcha = $db_questionConfig->getCaptcha();
    if($captcha != $realCaptcha){
        //验证码错误
        $db_player->submitWrongCaptcha($peopleId);

        $errPack->code = 0004;
        $errPack->info = "验证码输入错误!输错5次将被封号!";
        $db_questionConfig->close();
        die($errPack->parsePack());
    }
    $now = date("Y-m-d H:i:s");
    $ts =  floor(microtime()*1000);

    //通过了上面的验证码认证，说明这个人已经输入了验证码，可以清除消极作答的记录。
    $db_player->clearPassiveRecord($peopleId);

    //获取和问题相关的一些信息
    $questionPack = $db_questionConfig->getQuestionPack();
    $currentQuestionId = $questionPack['currentquestionid'];
    $peopleLimit = $questionPack['peoplelimit'];

    //记录输入验证码的时间
    $db_captchaDelay->recordInputTime($peopleId,$currentQuestionId,$now);

    //是否超过了人数限制，在2017的模式中不限制进入人数
//    if($db_questionBuffer->isReachPeopleLimit($currentQuestionId,$peopleLimit)){
//        $errPack->code = 0005;
//        $errPack->info = "现在已经达到了该题目的限制人数咯~不能再进了噢~";
//        die($errPack->parsePack());
//    }


    //把人插入到buffer表中，如果已经存在就不插入
    $result = $db_questionBuffer->insertPeopleIntoBuffer($peopleId,$currentQuestionId,$now,$ts);
    if($result == 0){
        //插入过程出错 or 连接出错
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }elseif($result == 2){
        //在表中存在这个人的情况(之前已经成功进入过题目了)
        $peopleState = $db_questionBuffer->getPeopleState($peopleId,$currentQuestionId);
        if($peopleState == QuestionBufferState::Done){
            $errPack->code = 0005;
            $errPack->info = "<script>scanstate('infopage','success::你刚才已经提交过答案啦~');</script>";
            $db_questionConfig->close();
            die($errPack->parsePack());
        }elseif($peopleState == QuestionBufferState::Timeout){
            $errPack->code = 0006;
            $errPack->info = "<script>scanstate('infopage','err::你刚才答题超时~不许再进了噢~');</script>";
            $db_questionConfig->close();
            die($errPack->parsePack());
        }
    }



    //通过了上面的判断以后，就可以将这个玩家的状态设置为 已经进入题目
    $db_questionBuffer->setPeopleState($peopleId,$currentQuestionId,QuestionBufferState::Joined);

    $db_questionConfig->close();

    $errPack->code = 0000;
    $errPack->info = "<script>scanstate('answer');</script>";
    die($errPack->parsePack());

}


function submitSolution($choose,$questionId){
    checkCAQlogin();

    $errPack = new errorpack();
    if(empty($choose)){
        $errPack->code = 0002;
        $errPack->info = "选项不能为空";
        die($errPack->parsePack());
    }

    if($choose != 'A' && $choose != 'B' && $choose != 'C' && $choose != 'D'){
        $errPack->code = 0002;
        $errPack->info = "提交的数据有问题";
        die($errPack->parsePack());
    }

    $db_questionConfig = new questionConfig();

    if(!$db_questionConfig->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }

    $db_player = new player($db_questionConfig->getMysqli());
    $db_questionBuffer = new questionBuffer($db_questionConfig->getMysqli());
    $db_captchaDelay = new CaptchaDelay($db_questionConfig->getMysqli());


    $currentQuestionId = $db_questionConfig->getCurrentQuestionId();
    //提交的题目ID 不是当前的题目ID
    if($currentQuestionId != $questionId){
        $errPack->code = 0003;
        $errPack->info = "<script>scanstate('infopage','err::你没有正确地进入到题目中来噢');</script>";
        $db_questionConfig->close();
        die($errPack->parsePack());
    }

    //还不是答题状态
    if(!$db_questionConfig->getIsAnswering()){
        $errPack->code = 0004;
        $errPack->info = "答题还没开始进行!请注视大屏幕~";
        $db_questionConfig->close();
        die($errPack->parsePack());
    }

    $peopleId = $_SESSION['peopleid'];

    //在 buffer 中找不到关于这个人的数据
    $peopleState = $db_questionBuffer->getPeopleState($peopleId,$currentQuestionId);
    if(!$peopleState){
        $errPack->code = 0005;
        $errPack->info = "<script>scanstate('infopage','err::你没有正确地进入到题目中来噢');</script>";
        $db_questionConfig->close();
        die($errPack->parsePack());
    }

    if($peopleState == QuestionBufferState::Done){
        //还没有数据表明会进入这个判断
        $errPack->code = 0006;
        $errPack->info = "<script>scanstate('infopage','err::你没有正确地进入到题目中来噢');</script>";
        $db_questionConfig->close();
        die($errPack->parsePack());

    }elseif($peopleState == QuestionBufferState::Waiting){
        //只有在2015、2016版本才有这个可能，一旦进入题目无需抢答，则表明这个判断不会进入。
        $errPack->code = 0007;
        $errPack->info = "<script>scanstate('infopage','err::你并没有抢到抢答权噢~');</script>";
        $db_questionConfig->close();
        die($errPack->parsePack());

    }elseif($peopleState == QuestionBufferState::Joined){
        $db_questionBuffer->submitSolution($peopleId,$questionId,$choose);
        $errPack->code = 0000;
        $errPack->info = "<script>scanstate('infopage','success::提交答案成功~');</script>";
        $db_questionConfig->close();
        die($errPack->parsePack());
    }
}




//提交 答案超时
function iveBeenTimeout($questionId){

    $errPack = new errorpack();

    $db_questionBuffer = new questionBuffer();

    if(!$db_questionBuffer->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }


    session_start();
    $peopleId = $_SESSION['peopleid'];

    //记录到数据库中
    if($db_questionBuffer->setPeopleState($peopleId,$questionId,QuestionBufferState::Timeout)){
        $errPack->code = 0002;
        $errPack->info = "操作中断了";
        $db_questionBuffer->close();
        die($errPack->parsePack());
    }

    $errPack->code = 0000;
    $errPack->info = "操作成功";
    $db_questionBuffer->close();
    die($errPack->parsePack());
}



//判断是不是老玩家
function isOldHand(){

    $errPack = new errorpack();

    $db_player = new player();

    if(!$db_player->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }

    session_start();
    $peopleId = $_SESSION['peopleid'];


    $responsePack = new stdClass();

    //判断新老手
    $isOldHand = $db_player->isOldHand($peopleId);
    if($db_player->getMysqli()->errno){
        $errPack->code = 0002;
        $errPack->info = "操作中断了,请重试";
        $db_player->close();
        die($errPack->parsePack());
    }

    $db_player->close();

    $responsePack->{'code'} = 0000;
    $responsePack->{'info'} = "操作成功";
    $responsePack->{'isOldHand'} = $isOldHand;
    die(json_encode($responsePack,JSON_UNESCAPED_UNICODE));
}

//获取个人资料
function getProfile(){

    $errPack = new errorpack();

    $db_player = new player();

    if(!$db_player->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }

    session_start();
    $peopleId = $_SESSION['peopleid'];
    $name = $_SESSION['name'];

    $profilePack = $db_player->getProfilePack($peopleId);
    if($db_player->getMysqli()->errno){
        $errPack->code = 0002;
        $errPack->info = "操作中断了，请重试";
        $db_player->close();
        die($errPack->parsePack());
    }

    $responsePack = new stdClass();
    $responsePack ->{'err'} = 0000;
    $responsePack ->{'name'} = $name;
    $responsePack ->{'score'} = $profilePack['score'];
    $responsePack ->{'ranking'} = $profilePack['ranking'];
    $responsePack ->{'oldranking'} = $profilePack['oldranking'];
    $responsePack ->{'rightcount'} = $profilePack['rightcount'];
    $responsePack ->{'wrongcount'} = $profilePack['wrongcount'];
    $responsePack ->{'isbanned'} = $profilePack['isbanned'];
    $responsePack ->{'active'} = $profilePack['active'];
    $responsePack ->{'reason-for-score'} = $profilePack['reason-for-score'];
    $responsePack ->{'achievetime'} = date("H:i:s",strtotime($profilePack['achievetime']));
    $db_player->close();
    die(json_encode($responsePack,JSON_UNESCAPED_UNICODE));
}

function getQuestionPack(){
    $errPack = new errorpack();

    $db_questionConfig = new questionConfig();

    if(!$db_questionConfig->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }

    $questionPack = $db_questionConfig->getQuestionPack();
    if($db_questionConfig->getMysqli()->errno){
        $errPack->code = 0002;
        $errPack->info = "操作中断了，请重试";
        $db_questionConfig->close();
        die($errPack->parsePack());
    }

    $responsePack = new stdClass();
    $responsePack ->{'err'} = 0000;
    $responsePack ->{'questionid'} = $questionPack['currentquestionid'];
    $responsePack ->{'peoplelimit'} = $questionPack['peoplelimit'];
    $responsePack ->{'addscore'} = $questionPack['addscore'];
    $responsePack ->{'minusscore'} = $questionPack['minusscore'];
    $responsePack ->{'question'} = $questionPack['question'];
    $responsePack ->{'availabletime'} = $questionPack['availabletime'];
    $responsePack ->{'answera'} = $questionPack['answera'];
    $responsePack ->{'answerb'} = $questionPack['answerb'];
    $responsePack ->{'answerc'} = $questionPack['answerc'];
    $responsePack ->{'answerd'} = $questionPack['answerd'];
    //数据库中A答案表示正确答案，而我们还有一个随机值用来确定最终的正确答案是哪一个，因此需要调换A答案和随机答案的位置
    $correctSolution = strtolower($questionPack['correctanswer']);
    $responsePack ->{'answer'.$correctSolution} = $questionPack['answera'];
    $responsePack ->{'answera'} = $questionPack['answer'.$correctSolution];
    $db_questionConfig->close();
    die(json_encode($responsePack,JSON_UNESCAPED_UNICODE));
}