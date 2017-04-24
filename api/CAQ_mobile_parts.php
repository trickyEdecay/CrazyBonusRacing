<?php //处理手机端页面?>
<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE"); 
?>

<?php
//判断操作数,分配到函数进行操作
header('Content-Type:text/html; charset=utf-8');//使用gb2312编码，使中文不会变成乱码
$what = $_POST['what'];
switch($what){
    
    //2016新改内容
    case 'getIdcPanelPack':
        getIdcPanelPack();
        break;
    case 'getAnswerPanelPack':
        getAnswerPanelPack();
        break;
}
?>



<?php
function getIdcPanelPack(){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        die();
    }
    session_start();
    $peopleid = $_SESSION['peopleid'];
    $name = $_SESSION['name'];
    
    $result = $nc->mysql("select oldranking,ranking,score,rightcount,wrongcount,achievetime,isbanned,active from question_people where id = $peopleid limit 1");
    
    if(!$result){
        $idcpanelpack ->{"err"} = 0001;
        $idcpanelpack ->{"errinfo"} = "连接出现了问题";
        die(json_encode($idcpanelpack));
    }
    
    $row = mysql_fetch_array($result);
    $idcpanelpack ->{"err"} = 0000;
    $idcpanelpack ->{"name"} = $name;
    $idcpanelpack ->{"score"} = $row['score'];
    $idcpanelpack ->{"ranking"} = $row['ranking'];
    $idcpanelpack ->{"oldranking"} = $row['oldranking'];
    $idcpanelpack ->{"rightcount"} = $row['rightcount'];
    $idcpanelpack ->{"wrongcount"} = $row['wrongcount'];
    $idcpanelpack ->{"isbanned"} = $row['isbanned'];
    $idcpanelpack ->{"active"} = $row['active'];
    $idcpanelpack ->{"achievetime"} = date("H:i:s",strtotime($row['achievetime']));
    
    die(json_encode($idcpanelpack));
}
?>




<?php
function getAnswerPanelPack(){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        die();
    }
    $index=0;
    $result = $nc->mysql("select value from question_config where keyname='questionpack' limit 1");
    
    if(!$result){
        $idcpanelpack ->{"err"} = 0001;
        $idcpanelpack ->{"errinfo"} = "连接出现了问题";
        die(json_encode($idcpanelpack));
    }
    
    $row = mysql_fetch_array($result);
    $questionpack = json_decode($row['value']);
    
    
    
    $idcpanelpack ->{"err"} = 0000;
    $idcpanelpack ->{"questionid"} = $questionpack->{'currentquestionid'};
    $idcpanelpack ->{"peoplelimit"} = $questionpack->{'peoplelimit'};
    $idcpanelpack ->{"addscore"} = $questionpack->{'addscore'};
    $idcpanelpack ->{"minusscore"} = $questionpack->{'minusscore'};
    $idcpanelpack ->{"question"} = $questionpack->{'question'};
    $idcpanelpack ->{"availabletime"} = $questionpack->{'availabletime'};
    $idcpanelpack ->{"answera"} = $questionpack->{'answera'};
    $idcpanelpack ->{"answerb"} = $questionpack->{'answerb'};
    $idcpanelpack ->{"answerc"} = $questionpack->{'answerc'};
    $idcpanelpack ->{"answerd"} = $questionpack->{'answerd'};
    $correctanswer = strtolower($questionpack->{'correctanswer'});
    $idcpanelpack ->{"answer".$correctanswer} = $questionpack->{'answera'};
    $idcpanelpack ->{"answera"} = $questionpack->{'answer'.$correctanswer};
    
    die(json_encode($idcpanelpack));
}
?>