<?php

/**
 * User: trickyEdecay
 * Date: 2017/4/25
 * Time: 19:49
 */
class question extends sqlihelper {

    private $_tableName = "question";

    public $peopleLimit;

    public $sort;

    public $addScore;

    public $minusScore;

    public $question;

    public $solutionA;
    public $solutionB;
    public $solutionC;
    public $solutionD;
    public $correctSolution;

    public $availableTime;


    /**
     * 通过 id 来获取某个问题
     * @param $id question 的 id
     * @return array 这个 question 的所有数据
     */
    public function getQuestionById($id){
        $this->result = $this->bindingQuery("select * from `{$this->_tableName}` where `id` = ? limit 1",
            "d",$id);
        return $this->fetchQuestionFromResult();
    }

    public function fetchQuestionFromResult(){
        if($row = $this->result->fetch_assoc()){
            $this->peopleLimit = $row['peoplelimit'];
            $this->sort = $row['sort'];
            $this->addScore = $row['addscore'];
            $this->minusScore = $row['minusscore'];
            $this->solutionA = addslashes($row['a']);
            $this->solutionB = addslashes($row['b']);
            $this->solutionC = addslashes($row['c']);
            $this->solutionD = addslashes($row['d']);
            $this->correctSolution = $row['randomtrue'];
            $this->question = addslashes($row['question']);
            $this->availableTime = $row['availabletime'];
            return $row;
        }
    }



}