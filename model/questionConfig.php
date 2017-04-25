<?php

/**
 * User: trickyEdecay
 * Date: 2017/4/25
 * Time: 20:00
 */
class questionConfig extends sqlihelper{

    private $_tableName = "question_config";

    public function setConfig($key,$value){
        $this->bindingQuery("update `{$this->_tableName}` set `value` = ? where `keyname` = ?",
            "ss",$value,$key);
    }

    public function setCurrentQuestionId($questionId){
        $this->setConfig("currentquestionid",$questionId);
    }
    public function setIsAnswering($isAnswering){
        $isAnswering = $isAnswering ? "true" : "false";
        $this->setConfig("isanswering",$isAnswering);
    }
    public function setAnswerState($answerState){
        $this->setConfig("answerstate",$answerState);
    }
    public function setQuestionPack($questionPack){
        $this->setConfig("questionpack",$questionPack);
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
}

class AnswerState{
    const sponsor = "showingsponsor";
    const captcha = "showingCaptcha";
    const question = "showingQuestion";
    const solution = "showingSolution";
    const rankingList = "showingrankingList";
}