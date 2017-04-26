<?php

/**
 * 关于游戏的加扣分规则会在这里面定义
 * User: trickyEdecay
 * Date: 2017/4/26
 * Time: 2:27
 */
class ScoreRules {

    public static function addScore($sqlihelper,$questionPack){
        self::addScore_V1($sqlihelper,$questionPack);
    }

    public static  function minusScore($sqlihelper,$questionPack){
        self::minusScore_V1($sqlihelper,$questionPack);
    }














    //------------------------------------------------------------------------------------------------------------------
    /*
     * V1 版本为 2015-2016年所用的一套加扣分规则
     *
     * */
    public static function addScore_V1($sqlihelper,$questionPack){

        $addScore = $questionPack['addscore'];
        $questionId = $questionPack['currentquestionid'];
        $correctSolution = $questionPack['correctanswer'];

        //答对加分
        $sqlihelper->mysql("update question_people,question_buffer 
            set question_people.score = question_people.score+$addScore,
            question_people.rightcount=question_people.rightcount+1,
            question_people.rightids=concat(IFNULL(question_people.rightids,''),'$questionId,'),
            achievetime = question_buffer.time, achievets = question_buffer.ts
            where 
            question_people.id = question_buffer.peopleid and 
            question_buffer.state='done' and 
            question_buffer.choose = '$correctSolution' and 
            question_buffer.questionid = $questionId"
        );

    }

    public static  function minusScore_V1($sqlihelper,$questionPack){

        $minusScore = $questionPack['minusscore'];
        $questionId = $questionPack['currentquestionid'];
        $correctSolution = $questionPack['correctanswer'];

        $year = YEAR;

        //答错扣分
        $sqlihelper->mysql("update question_people,question_buffer 
            set question_people.score = question_people.score-$minusScore,
            question_people.wrongcount=question_people.wrongcount+1,
            question_people.wrongids=concat(IFNULL(question_people.wrongids,''),'$questionId,'),
            achievetime = question_buffer.time, achievets = question_buffer.ts
            where 
            question_people.id = question_buffer.peopleid and 
            question_buffer.questionid = $questionId and
            (
            (question_buffer.state = 'done' and question_buffer.choose <> '$correctSolution') or
            question_buffer.state='joined' or
            question_buffer.state='timeout' or
            question_buffer.choose is null
            )"
        );


        //如果有连续两次没有输入验证码,那么就会被扣除分数,扣除的分数是 连续没有参加的次数*2
        $sqlihelper ->mysql("update question_people set score=score-active*2,activeminusscore=activeminusscore+active*2 where active>=2 and lastactiveyear='{$year}'");
        $sqlihelper ->mysql("update question_people set active=active+1 where lastactiveyear='{$year}'");
    }

    //------------------------------------------------------------------------------------------------------------------
}