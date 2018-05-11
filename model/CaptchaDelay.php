<?php

/**
 * User: trickyEdecay
 * Date: 2017/4/26
 * Time: 16:24
 */
class CaptchaDelay extends sqlihelper {


    /**
     * 计算出题的时间和第一个答题的人时间之间的间距
     * @param $questionId 问题id
     */
    public function calDelayTime($questionId){
        $result = $this->mysql("select time from question_buffer where questionid='{$questionId}' order by time desc limit 1");
        $row = $result->fetch_assoc();
        $fullTime = $row['time'];
        $this->mysql("update question_idcinputtime set delay=unix_timestamp(idcinputtime)-unix_timestamp('{$fullTime}') where questionid = '{$questionId}'");
    }

    /**
     * 记录输入验证码的时间，如果不存在记录则插入，存在则不插入。
     * @param $peopleId 用户id
     * @param $questionId 问题id
     * @param $now 当前时间
     */
    public function recordInputTime($peopleId, $questionId, $now){
        $this->result = $this->mysql("insert into `question_idcinputtime` 
          (`peopleid`,`questionid`,`idcinputtime`,`delay`) 
          select {$peopleId},{$questionId},'{$now}',0
          where not exists(
            select `peopleid`,`questionid` from `question_idcinputtime` where `peopleid` = {$peopleId} and `questionid` = {$questionId}
          )"
        );
    }
}