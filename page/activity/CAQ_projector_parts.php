<?php 
require_once("../config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');
?>

<?php
header('Content-Type:text/html; charset=utf-8');//使用gb2312编码，使中文不会变成乱码
$what = $_POST['what'];
switch($what){
    case 'scanstate':
    scanstate();
    break;
};

function scanstate(){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        die();
    }
    $result = $nc->mysql("select * from question_config where keyname='answerstate' limit 1");
    $row = mysql_fetch_array($result);
    switch($row['value']){
        case "idc":
            showidc();
            break;
        case "showingquestion":
            showquestion();
            break;
        case "showingkey":
            showkey();
            break;
        case "showingsponser":
            showsponser();
            break;
        case "showingprize":
            showprize();
            break;
    }
    
}
?>



<?php
function showquestion(){
    ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-sm-2 col-xs-2">
            <br>
            <img src="/page/img/banner_quanmindafengqiangxiao.png" class="img-responsive" alt="Responsive image">
            <br>
            <div class="div-top-block">
                <center>
                    <div class="div-top-title"><span class="glyphicon glyphicon-sort-by-attributes"></span>&nbsp;&nbsp;排行榜</div>
                </center>
                <?php
                    $nc = new sqlhelper();
                    $opecode = $nc->connect();
                    if($opecode!=0){
                        die();
                    }
                    $index=0;
                    $result = $nc->mysql("select * from question_people where isbanned=0 order by score desc,achievetime");
                    while($row = mysql_fetch_array($result)){
                        $index++;
                ?>
                <div class="div-top-row">
                    <div style="position:relative;height:45px;">
                        <div class="div-top-index-circle"><div class="div-top-index-number"><?php echo $index;?></div></div><span class="div-top-name">&nbsp;&nbsp;<?php echo $row['name']."({$row['score']}分)";?></span>
                        <br style="clear:both">
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
        <div class="col-md-10 col-sm-10 col-xs-10">
            <br>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <div class="div-projector-info-block">
                    <center>
                        <div class="col-md-12 col-sm-12 col-xs-12"><h2>
                            <?php 
                                $result = $nc->mysql("select * from question_config where keyname='idc' limit 1");
                                $row = mysql_fetch_array($result);
                                $idc = $row['value'];
                                echo $idc;
                            ?></h2></div>
                        <div class="col-md-12 col-sm-12 col-xs-12 div-projector-info-block-tips">验证码</div>
                        <br style="clear:both">
                    </center>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-3 col-xs-3">
                <div class="div-projector-info-block">
                    <center>
                        <div class="col-md-12 col-sm-12 col-xs-12"><h2>
                            <?php 
                                $result = $nc->mysql("select * from question_config where keyname='currentquestionid' limit 1");
                                $row = mysql_fetch_array($result);
                                $questionid = $row['value'];

                                $result = $nc->mysql("select * from question where id='$questionid' limit 1");
                                $row = mysql_fetch_array($result);
                                $peoplelimit = $row['peoplelimit'];
                                echo $peoplelimit;
                            ?></h2></div>
                        <div class="col-md-12 col-sm-12 col-xs-12 div-projector-info-block-tips">允许进入的人数</div>
                        <br style="clear:both">
                    </center>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-3 col-xs-3">
                <div class="div-projector-info-block">
                    <center>
                        <div class="col-md-12 col-sm-12 col-xs-12"><h2>
                            <?php 
                                $addscore = $row['addscore'];
                                echo "+$addscore";
                            ?></h2></div>
                        <div class="col-md-12 col-sm-12 col-xs-12 div-projector-info-block-tips">答对加分</div>
                        <br style="clear:both">
                    </center>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-3 col-xs-3">
                <div class="div-projector-info-block">
                    <center>
                        <div class="col-md-12 col-sm-12 col-xs-12"><h2>
                            <?php 
                                $minusscore = $row['minusscore'];
                                echo "-$minusscore";
                            ?></h2></div>
                        <div class="col-md-12 col-sm-12 col-xs-12 div-projector-info-block-tips">答错扣分</div>
                        <br style="clear:both">
                    </center>
                </div>
            </div>
            
            <br style="clear:both">
            <br style="clear:both">
            
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="div-projector-info-block">
                    <h2 style="padding:10px;font-family:'微软雅黑','黑体','Arial'">
                    <?php 
                        $question = $row['question'];
                        echo "$question";
                    ?>
                    </h2>
                    <br style="clear:both">
                </div>
            </div>
            
            <br style="clear:both">
            <br style="clear:both">
            
            <?php
                $answers = array('a'=>'','b'=>'','c'=>'','d'=>'');
                for($i=1;$i<4;$i++){
                    $ci = chr(97+$i);
                    $answers[$ci] = $row[$ci];
                }
                $rightanswer = strtolower($row['randomtrue']);
                $answers[$rightanswer] = $row['a'];
                $answers['a'] = $row[$rightanswer];
                
                for($i=0;$i<4;$i++){
                    ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="div-projector-answer-block">
                    <?php 
                        $ci = chr(97+$i);
                        echo strtoupper($ci).".".$answers[$ci];
                    ?>
                    <br style="clear:both">
                </div>
            </div>
            <br style="clear:both">
            <br style="clear:both">
            <?php
                }
            ?>
            
            
            
        </div>
    </div>
</div>
<?php
}
?>

















<?php
function showkey(){
    ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-sm-2 col-xs-2">
            <br>
            <img src="/page/img/banner_quanmindafengqiangxiao.png" class="img-responsive" alt="Responsive image">
            <br>
            <div class="div-top-block">
                <center>
                    <div class="div-top-title"><span class="glyphicon glyphicon-sort-by-attributes"></span>&nbsp;&nbsp;排行榜</div>
                </center>
                <?php
                    $nc = new sqlhelper();
                    $opecode = $nc->connect();
                    if($opecode!=0){
                        die();
                    }
                    $index=0;
                    $result = $nc->mysql("select * from question_people where isbanned=0 order by score desc,achievetime,achievets");
                    while($row = mysql_fetch_array($result)){
                        $index++;
                ?>
                <div class="div-top-row">
                    <div style="position:relative;height:45px;">
                        <div class="div-top-index-circle"><div class="div-top-index-number"><?php echo $index;?></div></div><span class="div-top-name">&nbsp;&nbsp;<?php echo $row['name']."({$row['score']}分)";?></span>
                        <br style="clear:both">
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
        <div class="col-md-10 col-sm-10 col-xs-10">
            <br>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <div class="div-projector-info-block">
                    <center>
                        <div class="col-md-12 col-sm-12 col-xs-12"><h2>
                            <?php 
                                $result = $nc->mysql("select * from question_config where keyname='idc' limit 1");
                                $row = mysql_fetch_array($result);
                                $idc = $row['value'];
                                echo $idc;
                            ?></h2></div>
                        <div class="col-md-12 col-sm-12 col-xs-12 div-projector-info-block-tips">验证码</div>
                        <br style="clear:both">
                    </center>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-3 col-xs-3">
                <div class="div-projector-info-block">
                    <center>
                        <div class="col-md-12 col-sm-12 col-xs-12"><h2>
                            <?php 
                                $result = $nc->mysql("select * from question_config where keyname='currentquestionid' limit 1");
                                $row = mysql_fetch_array($result);
                                $questionid = $row['value'];

                                $result = $nc->mysql("select * from question where id='$questionid' limit 1");
                                $row = mysql_fetch_array($result);
                                $peoplelimit = $row['peoplelimit'];
                                echo $peoplelimit;
                            ?></h2></div>
                        <div class="col-md-12 col-sm-12 col-xs-12 div-projector-info-block-tips">允许进入的人数</div>
                        <br style="clear:both">
                    </center>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-3 col-xs-3">
                <div class="div-projector-info-block">
                    <center>
                        <div class="col-md-12 col-sm-12 col-xs-12"><h2>
                            <?php 
                                $addscore = $row['addscore'];
                                echo "+$addscore";
                            ?></h2></div>
                        <div class="col-md-12 col-sm-12 col-xs-12 div-projector-info-block-tips">答对加分</div>
                        <br style="clear:both">
                    </center>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-3 col-xs-3">
                <div class="div-projector-info-block">
                    <center>
                        <div class="col-md-12 col-sm-12 col-xs-12"><h2>
                            <?php 
                                $minusscore = $row['minusscore'];
                                echo "-$minusscore";
                            ?></h2></div>
                        <div class="col-md-12 col-sm-12 col-xs-12 div-projector-info-block-tips">答错扣分</div>
                        <br style="clear:both">
                    </center>
                </div>
            </div>
            
            <br style="clear:both">
            <br style="clear:both">
            
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="div-projector-info-block">
                    <h2 style="padding:10px;font-family:'微软雅黑','黑体','Arial'">
                    <?php 
                        $question = $row['question'];
                        echo "$question";
                    ?>
                    </h2>
                    <br style="clear:both">
                </div>
            </div>
            
            <br style="clear:both">
            <br style="clear:both">
            
            <?php
                $answers = array('a'=>'','b'=>'','c'=>'','d'=>'');
                for($i=1;$i<4;$i++){
                    $ci = chr(97+$i);
                    $answers[$ci] = $row[$ci];
                }
                $rightanswer = strtolower($row['randomtrue']);
                $answers[$rightanswer] = $row['a'];
                $answers['a'] = $row[$rightanswer];
                
                for($i=0;$i<4;$i++){
                    $ci = chr(97+$i);
                    $divclass = $ci == $rightanswer?"div-projector-rightanswer-block":"div-projector-answer-block";
                    ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="<?php echo $divclass;?>">
                    <?php 
                        echo strtoupper($ci).".".$answers[$ci];
                    ?>
                    <br style="clear:both">
                </div>
            </div>
            <br style="clear:both">
            <br style="clear:both">
            <?php
                }
            ?>
            
            
            
        </div>
    </div>
</div>
<?php
}
?>














<?php
function showidc(){
    ?>
<div class="container-fluid" style="background-color:#C63D3D">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <br>
            
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="div-projector-info-block">
                    <center>
                        <div class="col-md-12 col-sm-12 col-xs-12"><h2 style="font-size:72px;color:#C63D3D">
                            <?php 
                                $nc = new sqlhelper();
                                $opecode = $nc->connect();
                                if($opecode!=0){
                                    die();
                                }
                                $result = $nc->mysql("select * from question_config where keyname='idc' limit 1");
                                $row = mysql_fetch_array($result);
                                $idc = $row['value'];
                                echo $idc;
                            ?></h2></div>
                        <div class="col-md-12 col-sm-12 col-xs-12 div-projector-info-block-tips">验证码</div>
                        <br style="clear:both">
                    </center>
                </div>
            </div>
            <br style="clear:both"><br style="clear:both">
            <center>
                <img src="/page/img/caq_sponser_projector.png" class="img-responsive" alt="Responsive image">
            </center>
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
        </div>
    </div>
</div>
<?php
}
?>






























<?php
function showsponser(){
    ?>
<div class="container-fluid" style="background-color:#C63D3D">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <br>
            
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="div-projector-info-block">
                    <center>
                        <div class="col-md-12 col-sm-12 col-xs-12"><h2 style="font-size:72px;color:#C63D3D;font-family:'微软雅黑','黑体','Arial'";>
                            验证码即将出现在这里~</h2></div>
                        <div class="col-md-12 col-sm-12 col-xs-12 div-projector-info-block-tips">验证码</div>
                        <br style="clear:both">
                    </center>
                </div>
            </div>
            <br style="clear:both"><br style="clear:both">
            <center>
                <img src="/page/img/caq_sponser_projector.png" class="img-responsive" alt="Responsive image">
            </center>
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
            <br style="clear:both"><br style="clear:both">
        </div>
    </div>
</div>
<?php
}
?>


















<?php
function showprize(){
    ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <br>
            
            <div class="div-top-block">
                <center>
                    <div class="div-top-title"><span class="glyphicon glyphicon-sort-by-attributes"></span>&nbsp;&nbsp;获奖名单</div>
                </center>
                <?php
                    $nc = new sqlhelper();
                    $opecode = $nc->connect();
                    if($opecode!=0){
                        die();
                    }
                    $index=0;
                    $limitsum = 1000;
                    $currentsum = 0;
                    $result = $nc->mysql("select score,name from question_people where isbanned=0 order by ranking");
                    while($row = mysql_fetch_array($result)){
                        if($currentsum+$row['score']<=$limitsum){
                            $currentsum=$currentsum+$row['score'];
                        }else{
                            break;
                        }
                        $index++;
                ?>
                <div class="div-top-row">
                    <div style="position:relative;height:45px;">
                        <div class="div-top-index-circle"><div class="div-top-index-number"><?php echo $index;?></div></div><span class="div-top-name">&nbsp;&nbsp;<?php echo $row['name']." - {$row['score']} 元";?></span>
                        <br style="clear:both">
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
