<?php

/**
 * User: trickyEdecay
 * Date: 2017/4/25
 * Time: 20:00
 */
class questionConfig extends sqlihelper{

    private $_tableName = "question_config";

    private $questionPack;

    public function setConfig($key,$value){
        $this->bindingQuery("update `{$this->_tableName}` set `value` = ? where `keyname` = ? limit 1",
            "ss",$value,$key);
    }

    public function getConfig($key){
        $this->result = $this->bindingQuery("select value from `{$this->_tableName}` where `keyname` = ? limit 1",
            "s",$key);
        return $this->result->fetch_assoc()['value'];
    }

    public function setCurrentQuestionId($questionId){
        $this->setConfig("currentquestionid",$questionId);
    }

    public function getCurrentQuestionId(){
        return $this->getConfig("currentquestionid");
    }

    public function setIsReging($isReging){
        $this->setConfig("isreging",$isReging);
    }

    public function getIsReging(){
         return $this->getConfig("isreging");
    }

    public function setIsAnswering($isAnswering){
        $isAnswering = $isAnswering ? "true" : "false";
        $this->setConfig("isanswering",$isAnswering);
    }

    public function getIsAnswering(){
        $isAnswering = $this->getConfig("isanswering");
        return $isAnswering == "true" ? true : false;
    }

    public function setAnswerState($answerState){
        $this->setConfig("answerstate",$answerState);
    }

    public function getAnswerState(){
        return $this->getConfig("answerstate");
    }

    public function setQuestionPack($questionPack){
        $this->setConfig("questionpack",$questionPack);
    }

    public function getQuestionPack(){
        $this->questionPack = $this->getConfig("questionpack");
        $this->questionPack = json_decode($this->questionPack,true);
        return $this->questionPack;
    }
    public function setQuestionStatistics($A,$B,$C,$D,$playerCount){
        $questionStatisticsPack = new stdClass();
        $questionStatisticsPack->{'A'} = $A;
        $questionStatisticsPack->{'B'} = $B;
        $questionStatisticsPack->{'C'} = $C;
        $questionStatisticsPack->{'D'} = $D;
        $questionStatisticsPack->{'playerCount'} = $playerCount;
        $en_questionStatisticsPack = json_encode($questionStatisticsPack);
        $this->setConfig("questionStatistics",$en_questionStatisticsPack);
    }

    public function setBalancePlayersPack($fastPlayerName,$firstCorrectPlayerName,$blackHorseName,$highHitRateName){
        $BalancePlayerInfo = new stdClass();
        $BalancePlayerInfo->{'fast'} = $fastPlayerName;
        $BalancePlayerInfo->{'firstCorrect'} = $firstCorrectPlayerName;
        $BalancePlayerInfo->{'blackHorse'} = $blackHorseName;
        $BalancePlayerInfo->{'highHitRate'} = $highHitRateName;
        $en_BalancePlayerInfo = json_encode($BalancePlayerInfo,JSON_UNESCAPED_UNICODE);
        $this->setConfig("balancePlayersPack",$en_BalancePlayerInfo);
    }

    public function setRankingListPack($rankingListPack){
        $this->setConfig("rankinglistpack",$rankingListPack);
    }

    /**
     * 设置问题开放时间，默认设置为当前时间
     */
    public function setQuestionOpenTime(){
        $now = date("Y-m-d H:i:s");
        $this->setConfig("questionopentime",$now);
    }

    /**
     * 生成验证码
     * @return int
     */
    public function generateCaptcha(){
        srand((double)microtime()*1000000);
        return rand(100000,999999);
    }

    public function setCaptcha($captcha = ""){
        if($captcha == ""){
            $captcha = $this->generateCaptcha();
        }
        $this->setConfig("idc",$captcha);
    }
    public function getCaptcha(){
        return $this->getConfig("idc");
    }


    public function computeQuestionStatistics($questionPack){
        $questionId = $questionPack['currentquestionid'];
        $this->mysql("select `id` from `question_buffer` where `questionid` = '{$questionId}' and `choose` = 'A' and `state` = 'done'");
        $selectA = $this->result->num_rows;
        $this->mysql("select `id` from `question_buffer` where `questionid` = '{$questionId}' and `choose` = 'B' and `state` = 'done'");
        $selectB = $this->result->num_rows;
        $this->mysql("select `id` from `question_buffer` where `questionid` = '{$questionId}' and `choose` = 'C' and `state` = 'done'");
        $selectC = $this->result->num_rows;
        $this->mysql("select `id` from `question_buffer` where `questionid` = '{$questionId}' and `choose` = 'D' and `state` = 'done'");
        $selectD = $this->result->num_rows;
        $this->mysql("select `id` from `question_buffer` where `questionid` = '{$questionId}' and `state` = 'done'");
        $playerCount = $this->result->num_rows;
        $this->setQuestionStatistics($selectA,$selectB,$selectC,$selectD,$playerCount);

    }

    public function computeBalancePlayerInfo($questionPack){
        $questionId = $questionPack['currentquestionid'];
        $correctSolution = $questionPack['correctanswer'];
        $this->mysql("select `peopleid` from `question_buffer` where `questionid` = '{$questionId}' order by `time` asc limit 1");
        $fastPlayerId = $this->result->fetch_assoc()['peopleid'];

        //手速最快
        $this->mysql("select `name` from `question_people` where `id` = '{$fastPlayerId}' limit 1");
        if($this->result->num_rows <= 0){
            $fastPlayerName = "::null";
        }else{
            $fastPlayerName = $this->result->fetch_assoc()['name'];
        }

        //最先答对
        $this->mysql("select `peopleid` from `question_buffer` where `questionid` = '{$questionId}' and `choose` = '{$correctSolution}' order by `done-time` asc limit 1");
        if($this->result->num_rows <= 0){
            $firstCorrectPlayerName = "::null";
        }else{
            $firstCorrectPlayerId = $this->result->fetch_assoc()['peopleid'];
            $this->mysql("select `name` from `question_people` where `id` = '{$firstCorrectPlayerId}' limit 1");
            $firstCorrectPlayerName = $this->result->fetch_assoc()['name'];
        }

        //排名变化最大
        $this->mysql("SELECT name,ranking-oldranking as `most` FROM `question_people` ORDER BY `most`  DESC limit 1");
        if($this->result->num_rows <= 0){
            $blackHorseName = "::null";
        }else{
            $blackHorseName = $this->result->fetch_assoc()['name'];
        }
        //准确率最高
        $this->mysql("SELECT name,round(rightcount/rightcount+wrongcount) as `most` FROM `question_people` ORDER BY `most` DESC limit 1");
        if($this->result->num_rows <= 0){
            $highHitRateName = "::null";
        }else{
            $highHitRateName = $this->result->fetch_assoc()['name'];
        }



        $this->setBalancePlayersPack($fastPlayerName,$firstCorrectPlayerName,$blackHorseName,$highHitRateName);

    }
}

class AnswerState{
    const sponsor = "showingSponsor";
    const captcha = "showingCaptcha";
    const question = "showingQuestion";
    const solution = "showingSolution";
    const winners = "showingWinners";
}