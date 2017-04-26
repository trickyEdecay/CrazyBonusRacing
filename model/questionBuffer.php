<?php

/**
 * User: trickyEdecay
 * Date: 2017/4/26
 * Time: 18:03
 */
class questionBuffer extends sqlihelper {

    private $_tableName = "question_buffer";

    //TODO 考虑这个sql语句怎么加上limit会比较合适
    public function getCountOfState($questionPack,$state){

        $currentQuestionId = $questionPack['currentquestionid'];

        $this->result = $this->mysql("select `id` from `{$this->_tableName}` where `questionid` = '{$currentQuestionId}' and `state` = '{$state}'");
        return $this->result->num_rows;
    }
}

class QuestionBufferState {

    const Joined = 'joined';

    const Done = "done";

    const Timeout = "timeout";
}