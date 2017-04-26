<?php

/**
 * User: trickyEdecay
 * Date: 2017/4/25
 * Time: 23:20
 */
class player extends sqlihelper {
    private $_tableName = "question_people";

    private $rankingListLength = 15;

    private $year = YEAR;

    /**
     * @var stdClass 排行榜数据包
     */
    public $rankingListPack;


    /**
     * 获取前 n 名的排行数据
     * @return string 数据的json字符串
     */
    public function getRankingListPack(){
        $this->result = $this->mysql("
            select `score`,`name`,`oldranking` from `{$this->_tableName}` 
            where `isbanned`=0 and `lastactiveyear`= {$this->year}
            order by `ranking` limit {$this->rankingListLength}
        ");

        $index = 0;
        $this->rankingListPack = new stdClass();
        while($row = $this->result->fetch_assoc()){
            $this->rankingListPack->{'people'}[$index] = new stdClass();
            $this->rankingListPack->{'people'}[$index]->{'name'} = $row['name'];
            $this->rankingListPack->{'people'}[$index]->{'score'} = $row['score'];
            $this->rankingListPack->{'people'}[$index]->{'oldranking'} = $row['oldranking'];
            $index++;
        }
        $en_rankingListPack = json_encode($this->rankingListPack,JSON_UNESCAPED_UNICODE); //需要php5.4支持
        return $en_rankingListPack;
    }

    /**
     * 重置分数低于0的人的分数到0分。
     */
    public function belowResetZero(){
        $this->mysql("update `{$this->_tableName}` set `score`=0 where `score`<0");
    }


    /**
     * 计算整个表的人名次排序
     */
    public function rank(){
        $year = YEAR;

        $this->mysql("set @ranking=0");
        $this->mysql("update 
            (
              select id,ranking,@ranking:=@ranking+1 as temprank from `{$this->_tableName}` 
              where lastactiveyear='{$year}' order by score desc,achievetime asc,achievets asc
            ) 
            temp,`{$this->_tableName}` set `{$this->_tableName}`.ranking = temp.temprank ,
            `{$this->_tableName}`.oldranking = temp.ranking 
            where `{$this->_tableName}`.id = temp.id"
        );

    }

    /**
     * 记录每个人的历史得分和历史排名
     */
    public function recordHistoryRankAndScore(){
        $year = YEAR;
        //记录历史分数和历史排名
        $this ->mysql("update `{$this->_tableName}` set 
            historyscore = concat(IFNULL(historyscore,''),concat(score,',')) ,
            historyranking = concat(IFNULL(historyranking,''),concat(ranking,',')) 
            where lastactiveyear='{$year}'"
        );
    }

}