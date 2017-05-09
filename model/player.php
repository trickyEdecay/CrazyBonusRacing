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

    private $defaultRanking = 66;

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

    /**
     * 用户提交错误的验证码执行这个操作
     * @param $peopleId 用户id
     */
    public function submitWrongCaptcha($peopleId){
        $this->mysql("update `question_people` set `wrongidccount`=`wrongidccount`+1 where `id`='{$peopleId}' limit 1");
        $this->mysql("update `question_people` set `isbanned`=1 where `wrongidccount`>=5 and `id`='{$peopleId}' limit 1");
    }


    /**
     * 清除消极作答（即没有输入验证码进入答题）的记录
     * @param $peopleId 用户id
     */
    public function clearPassiveRecord($peopleId){
        $this->bindingQuery("update `question_people` set `active`= 0 where `id`=?",
        "i",$peopleId
        );
    }


    public function getInfo($peopleId,$columns = "*"){
        $this->result = $this->mysql("select {$columns} from `question_people` where `id`='{$peopleId}' limit 1");
        return $this->result->fetch_assoc();
    }

    public function getRandomKey($peopleId){
        return $this->getInfo($peopleId,"`randomkey`")['randomkey'];
    }


    public function isOldHand($peopleId){
        $this->mysql("select `id` from `question_player_history` where `player-id` = {$peopleId} limit 1");
        if($this->result->num_rows <=0){
            return false;
        }else{
            return true;
        }
    }


    public function getProfilePack($peopleId){
        return $this->getInfo($peopleId,"oldranking,ranking,score,rightcount,wrongcount,achievetime,isbanned,active,`reason-for-score`");
    }

    public function isPlayerExist($name,$tel){
        $this->bindingQuery("select * from question_people where name=? and tel=? limit 1",
        "ss",
        $name,$tel
        );
        if($this->result->num_rows <= 0){
            return false;
        }else{
            return true;
        }
    }
    public function isTelExist($tel){
        $year = YEAR;
        $this->bindingQuery("select * from question_people where tel=? and lastactiveyear>{$year}-4 limit 1",
        "s",
        $tel
        );
        if($this->result->num_rows <= 0){
            return false;
        }else{
            return true;
        }
    }

    public function refreshRandomKey($name,$tel,$randomKey = ''){
        if($randomKey == ''){
            $randomKey =uniqid();
        }
        $this->mysql("update `question_people`
                      set 
                      `randomkey`='{$randomKey}'
                      where `name`='{$name}' and `tel`='{$tel}'
        ");
    }

    public function resetPlayer($name,$tel,$now,$ts){
        $this->mysql("update `question_people` 
                      set
                      `rightcount`='0',
                      `wrongcount`='0',
                      `score`='0',
                      `ranking`='{$this->defaultRanking}',
                      `oldranking`='{$this->defaultRanking}',
                      `achievetime`= now(6),
                      `achievets`='{$ts}',
                      `wrongidccount`='0',
                      `isbanned`='0',
                      `rightids`='',
                      `wrongids`='',
                      `active`='1',
                      `reason-for-score`='none',
                      `activeminusscore`='0',
                      `lastactiveyear`='{$this->year}',
                      `historyscore`='0,',
                      `historyranking`='{$this->defaultRanking},'
                      WHERE 
                      `name` = '{$name}' and `tel` = '{$tel}' limit 1
        ");
    }

    public function createNewPlayer($name,$tel,$now,$ts){
        $this->mysql("insert into question_people 
              (`name`,`tel`,`achievetime`,`achievets`,`participateyears`,`lastactiveyear`,
              `ranking`,`oldranking`,`historyscore`,`historyranking`,
              `rightcount`,`wrongcount`,`score`,`wrongidccount`,`isbanned`,
              `rightids`,`wrongids`,`active`,`activeminusscore`,`reason-for-score`
              ) 
              values
              ('{$name}','{$tel}',now(6),'{$ts}','{$this->year}','{$this->year}',
              '{$this->defaultRanking}','{$this->defaultRanking}','0,','{$this->defaultRanking},',
              0,0,0,0,0,
              '','',1,0,'none'
              )
        ");
    }


    public function setScoreChangeReasonToNone(){
        $this->mysql("update question_people set `reason-for-score` = 'none' where `lastactiveyear` = '{$this->year}'");
    }

    public function getPlayerCount(){
        return $this->mysql("select * from question_people where lastactiveyear = '{$this->year}'")->num_rows;
    }
}