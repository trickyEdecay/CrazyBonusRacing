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
    case "getAdminInfoPack":

        break;
    case "changeQuestionTo":

        break;
    case "showQuestion":

        break;
    case "showSolution":

        break;
    case "showSponsor":
        showSponsor($_POST['questionId']);
        break;
    case "showPrize":

        break;
    case "toggleReging":

        break;
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


    $errPack->code = 0000;
    $errPack->info = "成功";
    die($errPack->parsePack());

}