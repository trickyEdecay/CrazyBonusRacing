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

}