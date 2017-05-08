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


        //对这些答对的人进行加分
        $sqlihelper->mysql("update question_people as player,
                            (select * from question_buffer 
                                      where 
                                      `state` = 'done' and
                                      `choose` = '{$correctSolution}' and 
                                      `questionid` = '{$questionId}'
                                      order by `done-time` asc limit {$peopleLimit}
                            ) as buffer
                            set
                            player.score = player.score + $addScore,
                            player.rightcount = player.rightcount + 1,
                            player.rightids=concat(IFNULL(player.rightids,''),'$questionId,'),
                            player.achievetime = buffer.`done-time`,
                            player.achievets = buffer.ts,
                            player.`reason-for-score` = 'correct'
                            where
                            player.id = buffer.peopleid
        ");


        //对这些答对但是不是前面答对的人进行记录
        $sqlihelper->mysql("update question_people as player,
                            (select * from question_buffer 
                                      where 
                                      `state` = 'done' and
                                      `choose` = '{$correctSolution}' and 
                                      `questionid` = '{$questionId}'
                                      order by `done-time` asc limit {$peopleLimit},999
                            ) as buffer
                            set
                            player.rightcount = player.rightcount + 1,
                            player.rightids=concat(IFNULL(player.rightids,''),'$questionId,'),
                            player.`reason-for-score` = 'none'
                            where
                            player.id = buffer.peopleid
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

        //如果小于0 需要重置到 1，要不然下面的limit不生效
        $minusPlayerCount = $minusPlayerCount <= 0 ? 1 : $minusPlayerCount;



        //倒数15%的人里面：答错 & 答题超时 & 没有作答扣分
        $sqlihelper->mysql("update question_people as player,
                            (select * from question_buffer
                                      where
                                       `questionid` = '{$questionId}' and
                                       (
                                          (`state` = 'done' and `choose` <> '$correctSolution') or 
                                          `state` = 'joined' or
                                          `state` = 'wating' or 
                                          `state` = 'timeout' or 
                                          `choose` is null
                                       )
                                       order by `done-time` desc
                                       limit $minusPlayerCount
                            ) as buffer
                            set player.score = player.score-$minusScore,
                            player.wrongcount=player.wrongcount+1,
                            player.wrongids=concat(IFNULL(player.wrongids,''),'$questionId,'),
                            player.achievetime = now(6), 
                            player.achievets = buffer.ts,
                            player.`reason-for-score` = IF(buffer.state = 'done','last',buffer.state)
                            where 
                            player.id = buffer.peopleid"
        );

        //倒数15%以外没有作答、超时被扣分
        $sqlihelper->mysql("update question_people as player,
                            (select * from question_buffer
                                      where
                                       `questionid` = '{$questionId}' and
                                       (
                                          (`state` = 'done' and `choose` <> '$correctSolution') or 
                                          `state` = 'joined' or
                                          `state` = 'wating' or 
                                          `state` = 'timeout' or 
                                          `choose` is null
                                       )
                                       order by `done-time` desc
                                       limit $minusPlayerCount,999
                            ) as buffer
                            set player.score = player.score-$minusScore,
                            player.wrongcount=player.wrongcount+1,
                            player.wrongids=concat(IFNULL(player.wrongids,''),'$questionId,'),
                            player.achievetime = now(6), 
                            player.achievets = buffer.ts,
                            player.`reason-for-score` = IF(buffer.state = 'done','last',buffer.state)
                            where 
                            player.id = buffer.peopleid and 
                            buffer.state <> 'done'
                            "
        );

        //0分题可以作为不扣分的题
        if($minusScore>=1){
            //凡是答错扣除自身分数的百分之多少
            $minusRate = 0.05;

            //当分数大于这个值的时候才会答错被扣分
            $threshold = 15;

            //凡是答错都得扣分,只有当分数大于15分的时候才会被扣分
            $sqlihelper->mysql("update question_people as player,
                            (select * from question_buffer
                                      where
                                       `questionid` = '{$questionId}' and
                                       (
                                          (`state` = 'done' and `choose` <> '$correctSolution') or 
                                          `state` = 'joined' or
                                          `state` = 'wating' or 
                                          `state` = 'timeout' or 
                                          `choose` is null
                                       )
                                       order by `done-time` desc
                                       limit $minusPlayerCount,999
                            ) as buffer
                            set player.score = player.score-round(player.score * '{$minusRate}'),
                            player.wrongcount=player.wrongcount+1,
                            player.wrongids=concat(IFNULL(player.wrongids,''),'$questionId,'),
                            player.achievetime = buffer.`done-time`, 
                            player.achievets = buffer.ts,
                            player.`reason-for-score` = 'wrong'
                            where 
                            player.score > '{$threshold}' and
                            player.id = buffer.peopleid and 
                            buffer.state = 'done'
                            "
            );
        }



        //如果有连续两次没有输入验证码,那么就会被扣除分数,扣除的分数是 连续没有参加的次数*2
        $sqlihelper ->mysql("update question_people set score=score-active*2,`achievetime`=now(6),activeminusscore=activeminusscore+active*2,`reason-for-score` = 'passive' where active>2 and lastactiveyear='{$year}'");
        $sqlihelper ->mysql("update question_people set active=active+1 where lastactiveyear='{$year}'");
    }

    //------------------------------------------------------------------------------------------------------------------
}