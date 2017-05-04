<?php
/**
 * 控制投影幕相关的数据将会走这个模块
 *
 * User: trickyEdecay
 * Date: 2017/4/25
 * Time: 20:05
 */

require_once ("checkString.php");
require_once ("encrypt.php");

if(!isset($_POST['what'])){
    die();
}
$what = $_POST['what'];
switch ($what){
    case "getQuestionStatus":
        getQuestionStatus();
        break;
    case "showCaptcha":
        showCaptcha();
        break;
    case "showQuestion":
        showQuestion();
        break;
    case "showSolution":
        showSolution();
        break;
    case "showSponsor":
        showSponsor($_POST['questionId']);
        break;
    case "showWinners":
        showWinners();
        break;
    case "toggleReging":
        toggleReging($_POST['changeTo']);
        break;
}

//获取问题状态信息
function getQuestionStatus(){

    $db_questionConfig = new questionConfig();

    $errPack = new errorpack();

    if(!$db_questionConfig->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }

    $questionPack = $db_questionConfig->getQuestionPack();
    $currentQuestionId = $questionPack['currentquestionid'];
    $peopleLimit = $questionPack['peoplelimit'];
    $question = $questionPack['question'];
    $sort = $questionPack['sort'];
    $addScore = $questionPack['addscore'];
    $minusScore = $questionPack['minusscore'];

    $answerState = $db_questionConfig->getAnswerState();

    $questionBuffer = new questionBuffer($db_questionConfig->getMysqli());
    $peopleJoined = $questionBuffer->getCountOfState($questionPack,QuestionBufferState::Joined);
    $peopleDone = $questionBuffer->getCountOfState($questionPack,QuestionBufferState::Done);
    $peopleTimeout = $questionBuffer->getCountOfState($questionPack,QuestionBufferState::Timeout);

    $db_questionConfig->close();

    $questionStatusPack = new stdClass();
    $questionStatusPack->{'code'} = 0000;
    $questionStatusPack->{'info'} = "操作成功";
    $questionStatusPack->{'questionstate'} = $answerState;
    $questionStatusPack->{'currentquestionid'} = $currentQuestionId;
    $questionStatusPack->{'peoplelimit'} = $peopleLimit;
    $questionStatusPack->{'sort'} = $sort;
    $questionStatusPack->{'question'} = $question;
    $questionStatusPack->{'addscore'} = $addScore;
    $questionStatusPack->{'minusscore'} = $minusScore;
    $questionStatusPack->{'peoplejoined'} = $peopleJoined;
    $questionStatusPack->{'peopledone'} = $peopleDone;
    $questionStatusPack->{'peopletimeout'} = $peopleTimeout;
    die(json_encode($questionStatusPack));

}

//显示赞助商
function showSponsor($questionId){

    $db_questionConfig = new questionConfig();

    $errPack = new errorpack();

    if(!$db_questionConfig->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }

    //将系统设置为 赞助商页面 状态
    $db_questionConfig->setCurrentQuestionId($questionId);
    $db_questionConfig->setIsAnswering(false);
    $db_questionConfig->setAnswerState(AnswerState::sponsor);
    $db_questionConfig->setQuestionOpenTime();
    $db_questionConfig->setCaptcha();

    if(!$db_questionConfig->isSuccess()){
        $errPack->code = 0002;
        $errPack->info = "操作中断了,请重试";
        die($errPack->parsePack());
    }

    $db_question = new question($db_questionConfig->getMysqli());
    $db_question->getQuestionById($questionId);


    //在数据库中预置问题
    $questionPack = new stdClass();
    $questionPack->{'currentquestionid'} = $questionId;
    $questionPack->{'peoplelimit'} = $db_question->peopleLimit;
    $questionPack->{'sort'} = $db_question->sort;
    $questionPack->{'addscore'} = $db_question->addScore;
    $questionPack->{'minusscore'} = $db_question->minusScore;
    $questionPack->{'availabletime'} = $db_question->availableTime;
    $questionPack->{'answera'} = $db_question->solutionA;
    $questionPack->{'answerb'} = $db_question->solutionB;
    $questionPack->{'answerc'} = $db_question->solutionC;
    $questionPack->{'answerd'} = $db_question->solutionD;
    $questionPack->{'correctanswer'} = $db_question->correctSolution;
    $questionPack->{'question'} = $db_question->question;

    $en_questionPack = json_encode($questionPack,JSON_UNESCAPED_UNICODE); //需要php5.4支持
    if(!$db_questionConfig->isSuccess()){
        $errPack->code = 0002;
        $errPack->info = "操作中断了,请重试";
        die($errPack->parsePack());
    }

    //在数据库中预置问题
    $db_questionConfig->setQuestionPack($en_questionPack);

    //在数据库中预置排行榜
    $player = new player($db_questionConfig->getMysqli());
    $rankingListPack = $player->getRankingListPack();
    $db_questionConfig->setRankingListPack($rankingListPack);

    $db_questionConfig->close();

    $errPack->code = 0000;
    $errPack->info = "成功";
    die($errPack->parsePack());

}

//显示验证码
function showCaptcha(){

    $db_questionConfig = new questionConfig();

    $errPack = new errorpack();

    if(!$db_questionConfig->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }

    //将系统设置为 显示验证码 状态
    $db_questionConfig->setAnswerState(AnswerState::captcha);
    $db_questionConfig->setIsAnswering(true);
    if(!$db_questionConfig->isSuccess()){
        $errPack->code = 0002;
        $errPack->info = "操作中断了,请重试";
        die($errPack->parsePack());
    }

    $db_questionConfig->close();

    $errPack->code = 0000;
    $errPack->info = "成功";
    die($errPack->parsePack());

}


//显示题目
function showQuestion(){

    $db_questionConfig = new questionConfig();

    $errPack = new errorpack();

    if(!$db_questionConfig->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }

    //将系统设置为 显示页面 状态
    $db_questionConfig->setAnswerState(AnswerState::question);
    $db_questionConfig->setIsAnswering(true);
    if(!$db_questionConfig->isSuccess()){
        $errPack->code = 0002;
        $errPack->info = "操作中断了,请重试";
        die($errPack->parsePack());
    }

    $db_questionConfig->close();

    $errPack->code = 0000;
    $errPack->info = "成功";
    die($errPack->parsePack());
}

//显示答案
function showSolution(){

    $db_questionConfig = new questionConfig();

    $errPack = new errorpack();

    if(!$db_questionConfig->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }

    //将系统设置为 显示答案 状态
    $db_questionConfig->setAnswerState(AnswerState::solution);
    $db_questionConfig->setIsAnswering(false);
    if(!$db_questionConfig->isSuccess()){
        $errPack->code = 0002;
        $errPack->info = "操作中断了,请重试";
        die($errPack->parsePack());
    }

    $now = date("Y-m-d H:i:s");

    //要用毫秒来记录先后顺序
    $ts =  floor(microtime()*1000);

    //获取题目概要
    $questionPack = $db_questionConfig->getQuestionPack();
    $questionId = $questionPack['currentquestionid'];

    $db_player = new player($db_questionConfig->getMysqli());

    //先把分数变化的原因重置为 none
    $db_player->setScoreChangeReasonToNone();

    ScoreRules::addScore($db_questionConfig,$questionPack);
    ScoreRules::minusScore($db_questionConfig,$questionPack);


    //重置分数低于0 的为0分。
    $db_player->belowResetZero();

    //计算出各个选项选择的人数及答题的人数存数据库，用于显示统计数据
    $db_questionConfig->computeQuestionStatistics($questionPack);

    //计算这次答题里面手速最快的和第一个答对的人，并存到数据库里面
    $db_questionConfig->computeBalancePlayerInfo($questionPack);

    //进行分数排名存数据库
    $db_player->rank();

    //保存历史历史分数和历史排名
    $db_player->recordHistoryRankAndScore();

    //在数据库中预置分数排行榜
    $rankingListPack = $db_player->getRankingListPack();
    $db_questionConfig->setRankingListPack($rankingListPack);

    $captchaDelay = new CaptchaDelay($db_questionConfig->getMysqli());
    $captchaDelay->calDelayTime($questionId);

    $db_questionConfig->close();

    $errPack->code = 0000;
    $errPack->info = "成功";
    die($errPack->parsePack());

}

//显示获奖名单
function showWinners(){

    $db_questionConfig = new questionConfig();

    $errPack = new errorpack();

    if(!$db_questionConfig->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }

    //将系统设置为 显示页面 状态
    $db_questionConfig->setAnswerState(AnswerState::winners);
    $db_questionConfig->setIsAnswering(false);
    if(!$db_questionConfig->isSuccess()){
        $errPack->code = 0002;
        $errPack->info = "操作中断了,请重试";
        die($errPack->parsePack());
    }

    $db_questionConfig->close();

    $errPack->code = 0000;
    $errPack->info = "成功";
    die($errPack->parsePack());
}


function toggleReging($changeTo){
    $db_questionConfig = new questionConfig();

    $errPack = new errorpack();

    if(!$db_questionConfig->connect()){
        $errPack->code = 0001;
        $errPack->info = "连接出错";
        die($errPack->parsePack());
    }

    $db_questionConfig->setIsReging($changeTo);
    if(!$db_questionConfig->isSuccess()){
        $errPack->code = 0002;
        $errPack->info = "操作中断了,请重试";
        die($errPack->parsePack());
    }

    $db_questionConfig->close();

    $errPack->code = 0000;
    $errPack->info = "成功";
    die($errPack->parsePack());
}