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

    /**
     * 某道题进入的人数是否已经超过了某个限度了。
     * @param $questionId 问题id
     * @param $peopleLimit 人数限制
     * @return bool 超过将返回true，没超过将返回false。
     */
    public function isReachPeopleLimit($questionId, $peopleLimit){
        $this->result = $this->mysql("select `peopleid` from `question_buffer` where questionid='{$questionId}' limit {$peopleLimit}");
        if($this->result->num_rows >= $peopleLimit){
            return true;
        }else{
            return false;
        }
    }

    public function insertPeopleIntoBuffer($peopleId,$questionId,$now,$ts){
        $state = QuestionBufferState::Waiting;
        $this->result = $this->mysql("insert into `question_buffer` 
            (`peopleid`,`questionid`,`time`,`done-time`,`state`,`ts`)
            select * from (select {$peopleId},{$questionId},now(6),'9999-12-31 00:00:00:000000','{$state}',{$ts}) as tmp
            where not exists(
              select `peopleid`,`questionid` from `question_buffer` where `peopleid` = {$peopleId} and `questionid` = {$questionId} limit 1
            )"
        );
        if($this->isSuccess()){
            if($this->_mysqli->affected_rows>0){
                //成功插入
                return 1;
            }else{
                //表中已经存在
                return 2;
            }
        }else{
            return 0;
        }
    }

    public function getPeopleState($peopleId,$questionId){
        $this->result = $this->bindingQuery("select `state` from `question_buffer` 
          where `peopleid`= ? and `questionid`= ? limit 1",
            "ii",
            $peopleId,$questionId
        );
        //查不到数据的话
        if($this->result->num_rows <= 0){
            return false;
        }
        $row = $this->result->fetch_assoc();
        if($row['state']==QuestionBufferState::Done){
            return QuestionBufferState::Done;
        }elseif($row['state']==QuestionBufferState::Timeout){
            return QuestionBufferState::Timeout;
        }elseif($row['state']==QuestionBufferState::Joined){
            return QuestionBufferState::Joined;
        }elseif($row['state']==QuestionBufferState::Waiting){
            return QuestionBufferState::Waiting;
        }

    }


    public function setPeopleState($peopleId,$questionId,$state){
        $this->mysql("update question_buffer set state='{$state}' where 
            questionid='{$questionId}' and peopleid='{$peopleId}' limit 1"
        );
        if($this->_mysqli->errno == 0){
            return true;
        }else{
            return false;
        }
    }


    public function submitSolution($peopleId,$questionId,$choose){
        $state = QuestionBufferState::Done;
        $this->mysql("update question_buffer set state='{$state}',choose='{$choose}',`done-time`= now(6)
            where questionid='{$questionId}' and peopleid='{$peopleId}' limit 1"
        );
    }

}

class QuestionBufferState {

    const Waiting = 'waiting';

    const Joined = 'joined';

    const Done = "done";

    const Timeout = "timeout";
}