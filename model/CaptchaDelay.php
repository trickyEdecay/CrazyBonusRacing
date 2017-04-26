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
}