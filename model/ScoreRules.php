<?php

/**
 * 关于游戏的加扣分规则会在这里面定义
 * User: trickyEdecay
 * Date: 2017/4/26
 * Time: 2:27
 */
class ScoreRules {

    public static function addScore($sqlihelper,$questionPack){
        self::addScore_V2($sqlihelper,$questionPack);
    }

    public static  function minusScore($sqlihelper,$questionPack){
        self::minusScore_V2($sqlihelper,$questionPack);
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













    //------------------------------------------------------------------------------------------------------------------
    /*
     * V2 版本为 2017年所用的一套加扣分规则
     *
     * */
    /**
     * @param sqlihelper $sqlihelper
     * @param array $questionPack
     */
    public static function addScore_V2($sqlihelper, $questionPack){

        $addScore = $questionPack['addscore'];
        $questionId = $questionPack['currentquestionid'];
        $correctSolution = $questionPack['correctanswer'];
        $peopleLimit = $questionPack['peoplelimit'];

        //获取前 n 名答对的人中，第n名玩家在buffer表中的id
        $result = $sqlihelper->mysql("select id from
                            question_buffer
                            where
                            questionid = '{$questionId}' and
                            choose = '{$correctSolution}' and
                            state = 'done'
                            order by `time` asc limit $peopleLimit
        ");

        $result->data_seek($result->num_rows-1);
        $theLastCorrectPlayerId = $result->fetch_row()[0];

        //对这些答对的人进行加分
        $sqlihelper->mysql("update question_people as player,question_buffer as buffer
                            set
                            player.score = player.score + $addScore,
                            player.rightcount = player.rightcount + 1,
                            player.rightids=concat(IFNULL(player.rightids,''),'$questionId,'),
                            player.achievetime = buffer.time,
                            player.achievets = buffer.ts
                            where
                            player.id = buffer.peopleid and 
                            buffer.state = 'done' and
                            buffer.choose = '$correctSolution' and
                            buffer.questionid = $questionId and 
                            buffer.id <= {$theLastCorrectPlayerId}
        ");

    }

    /**
     * @param sqlihelper $sqlihelper
     * @param array $questionPack
     */
    public static  function minusScore_V2($sqlihelper,$questionPack){

        $minusScore = $questionPack['minusscore'];
        $questionId = $questionPack['currentquestionid'];
        $correctSolution = $questionPack['correctanswer'];

        $year = YEAR;

        //总共有多少玩家
        $playerCount = $sqlihelper->mysql("select * from question_people where lastactiveyear = {$year}")->num_rows;

        //倒数百分之x 的人扣分
        $rateOfMinus = 0.15;

        //要被扣分的人数
        $minusPlayerCount = round($playerCount * $rateOfMinus);

        //找出在buffer中倒数第n个答错的人的id。
        $result = $sqlihelper->mysql("select id from
                            question_buffer
                            where
                            questionid = '{$questionId}' and
                            choose <> '{$correctSolution}' and
                            state = 'done'
                            order by `time` desc limit $minusPlayerCount
        ");

        $result->data_seek($result->num_rows-1);
        $theLastWrongPlayerId = $result->fetch_row()[0];


        //倒数答错 & 答题超时 & 没有作答扣分
        $sqlihelper->mysql("update question_people,question_buffer 
            set question_people.score = question_people.score-$minusScore,
            question_people.wrongcount=question_people.wrongcount+1,
            question_people.wrongids=concat(IFNULL(question_people.wrongids,''),'$questionId,'),
            achievetime = question_buffer.time, achievets = question_buffer.ts
            where 
            question_people.id = question_buffer.peopleid and 
            question_buffer.questionid = $questionId and
            (
            (question_buffer.state = 'done' and question_buffer.choose <> '$correctSolution' and question_buffer.id>={$theLastWrongPlayerId}) or
            question_buffer.state='joined' or
            question_buffer.state='waiting' or
            question_buffer.state='timeout' or
            question_buffer.choose is null
            )"
        );

        //0分题可以作为不扣分的题
        if($minusScore>1){
            //凡是答错扣除自身分数的百分之多少
            $minusRate = 0.05;

            //当分数大于这个值的时候才会答错被扣分
            $threshold = 15;

            //凡是答错都得扣分,只有当分数大于15分的时候才会被扣分
            $sqlihelper->mysql("update question_people,question_buffer 
            set question_people.score = question_people.score-round(question_people.score * '{$minusRate}'),
            question_people.wrongcount=question_people.wrongcount+1,
            question_people.wrongids=concat(IFNULL(question_people.wrongids,''),'$questionId,'),
            achievetime = question_buffer.time, achievets = question_buffer.ts
            where 
            question_people.score > '{$threshold}' and
            question_people.id = question_buffer.peopleid and 
            question_buffer.questionid = $questionId and
            question_buffer.state = 'done' and question_buffer.choose <> '$correctSolution' and question_buffer.id<{$theLastWrongPlayerId}
            "
            );
        }



        //如果有连续两次没有输入验证码,那么就会被扣除分数,扣除的分数是 连续没有参加的次数*2
        $sqlihelper ->mysql("update question_people set score=score-active*2,activeminusscore=activeminusscore+active*2 where active>2 and lastactiveyear='{$year}'");
        $sqlihelper ->mysql("update question_people set active=active+1 where lastactiveyear='{$year}'");
    }

    //------------------------------------------------------------------------------------------------------------------
}