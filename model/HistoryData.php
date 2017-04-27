<?php

/**
 * User: trickyEdecay
 * Date: 2017/4/27
 * Time: 17:49
 */
class HistoryData extends sqlihelper {

    public function pushPlayerHistoryData($playerId,$rightCount,$wrongCount,$score,$ranking,$year){
        $this->mysql("insert into `question_player_history` 
            (`player-id`,`right-count`,`wrong-count`,`score`,`ranking`,`year`)
            VALUES 
            ('{$playerId}','{$rightCount}','{$wrongCount}','{$score}','{$ranking}','{$year}')
        ");
    }
}