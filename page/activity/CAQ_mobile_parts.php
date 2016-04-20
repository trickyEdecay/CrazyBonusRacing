<?php //处理手机端页面?>
<?php
require_once("../config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/checkString.php');

ini_set("error_reporting","E_ALL & ~E_NOTICE"); 
?>

<?php
//判断操作数,分配到函数进行操作
header('Content-Type:text/html; charset=utf-8');//使用gb2312编码，使中文不会变成乱码
$what = $_POST['what'];
switch($what){
    case 'idc':
        showidc();
        break;
    case 'answer':
        showanswer();
        break;
    case 'infopage':
        showinfopage($_POST['appendix']);
        break;
}
?>


<?php
function showidc(){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        die();
    }
    session_start();
    $peopleid = $_SESSION['peopleid'];
    $name = $_SESSION['name'];
    
    $result = $nc->mysql("select ranking,score,rightcount,wrongcount,achievetime from question_people where id = $peopleid limit 1");
    $row = mysql_fetch_array($result);
    $score = $row['score'];
    $ranking = $row['ranking'];
    $rightcount = $row['rightcount'];
    $wrongcount = $row['wrongcount'];
    $achievetime = $row['achievetime'];
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <img src="/page/img/banner_quanmindafengqiang_ad.jpg" class="img-responsive" style="margin-top:15px;" alt="Responsive image">
    <center>
        <p style="color:#BEBEBE;margin-top:8px;">本活动赞助商[中国移动]+[创益凡]</p>
        <br>
        <div class="panel panel-default">
          <!-- Default panel contents -->
          <div class="panel-heading">这是关于你的统计情况~</div>

          <!-- List group -->
          <ul class="list-group">
            <li class="list-group-item"><?php echo "你的名字:  $name";?></li>
            <li class="list-group-item"><?php echo "你的排名:  $ranking";?></li>
            <li class="list-group-item"><?php echo "你的分数:  $score";?></li>
            <li class="list-group-item"><?php echo "达到这个分数的时间:  $achievetime";?></li>
            <li class="list-group-item"><?php echo "答对题目总数:  $rightcount";?></li>
            <li class="list-group-item"><?php echo "答错题目总数:  $wrongcount";?></li>
            <li class="list-group-item"><button type="button" class="btn btn-default" id="refresh">点此刷新</button></li>
          </ul>
        </div>
        <br>
        <div class="div-input-border col-md-12 col-sm-12 col-xs-12 " style="font-size:20px;">
            <input id="idcInput" placeholder="输入投影上的验证码进入答题噢~" style="text-align:center;">
            <span style="color:#787878"></span>
            <br style="clear:both">
        </div>
        
        <button class="div-caq-button col-md-12 col-sm-12 col-xs-12" id="goAnswer">进入疯狂抢答</button>
        <script>
        $(document).ready(function(){
            $("#refresh").click(function(){
                $("#refresh").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;刷新中");
                scanstate("idc");
            });
            
            $("#goAnswer").click(function(){
                if($("#goAnswer").html()=="进入疯狂抢答"){
                    $("#goAnswer").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;正在进入中,稍安勿躁");
                    $.ajax({
                        url: "<?php echo PAGE_PATH;?>php/CAQ_function.php",
                        type: "POST",
                        data:{
                            'what':'checkidc',
                            'idc':$('#idcInput').val()
                        },
                        success: function(data,status){
                            data = JSON.parse(data);
                            if(data.code == 0000){
                                $("#logintips").html(data.info);
                            }else{
                                $("#logintips").html(data.info);
                                $("#goAnswer").html("进入疯狂抢答");
                            }
                        }
                    });
                
            }
            });
        });
        </script>
        <p id="logintips" class="p-error"></p>
        <br style="clear:both">
        <br>
        <br>
        <br>
        <p class="p-bottom-extras">科创社 2014 技术提供</p>
    </center>
</div>
<?php
}
?>





<?php
function showanswer(){
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        die();
    }
    $index=0;
    $result = $nc->mysql("select value from question_config where keyname='currentquestionid'");
    $row = mysql_fetch_array($result);
    $questionid = $row['value'];
    
    session_start();
    $peopleid = $_SESSION['peopleid'];
    $name = $_SESSION['name'];
    
    $result = $nc->mysql("set @ranking=0");
    $result = $nc->mysql("select name,score,@ranking:=@ranking+1 as ranking from question_people order by score,achievetime");
    $row = mysql_fetch_array($result);
    $ranking = $row['ranking'];
    $score = $row['score'];
    
    $result = $nc->mysql("select * from question where id='$questionid'");
    $row = mysql_fetch_array($result);
    $peoplelimit = $row['peoplelimit'];
    $addscore = $row['addscore'];
    $minusscore = $row['minusscore'];
    $question = $row['question'];
    $availabletime = $row['availabletime'];
    
?>
<div class="container-fluid" style="padding:0;">
    <br>
    <div class="col-md-1 col-sm-1 col-xs-1">
        <div class="div-answer-index-circle"><div class="div-answer-index-number"><?php echo $ranking;?></div></div>
    </div>
    <div class="col-md-8 col-sm-8 col-xs-8">
        <span class="div-answer-mobile-name">&nbsp;&nbsp;<?php echo $name;?></span><span class="div-answer-mobile-score">&nbsp;&nbsp;(我的总分:&nbsp;<?php echo $score;?>)</span>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1" style="padding-left: 0px;">
        <div class="div-answer-addscore-block"><?php echo "+$addscore";?></div>
    </div>
    <div class="col-md-1 col-sm-1 col-xs-1">
        <div class="div-answer-minusscore-block"><?php echo "-$minusscore";?></div>
    </div>
    <br style="clear:both">
    <br style="clear:both">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="div-answer-question-block"><?php echo "$question";?></div>
    </div>
    
    <br style="clear:both">
    <br style="clear:both">
    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="timebar" id="timebar">
            <div class="timebarvalue" id="timebarvalue"></div>
        </div>
    </div>
    
    <div class="col-md-12 col-sm-12 col-xs-12" id="errorbox" style="display:none;">
        <br style="clear:both">
        <br style="clear:both">
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" id="alertclose"><span aria-hidden="true">&times;</span></button>
            <span id="errortips"></span>
        </div>
    </div>
    <script>
    var availabletime = <?php echo $availabletime; ?> *1000;
    var now = availabletime;
    var timer1 = setInterval('timejudge()',10);
    $(document).ready(function(){
        
        
        
        
        $("#alertclose").click(function(){
            $("#errorbox").css("display","none");
        });
    });
        
    function timejudge(){
        if(now<0){
            $("#answerbuttongroup").css("display","none");
            $("#processinganimation").css("display","inline");
            clearInterval(timer1);
            scanstate('infopage','err::你没有在规定的时间内作答~');
            
            $.ajax({
                url: "<?php echo PAGE_PATH;?>php/CAQ_function.php",
                type: "POST",
                data:{
                    'what':'submitanswertimeout',
                    'questionid':'<?php echo $questionid;?>'
                },
                success: function(data,status){

                }
            });
        }
        now = now - 10;
        var widthpercent = now/availabletime * 100;
        $("#timebarvalue").css('width',widthpercent+"%");
    }
    </script>
    
    <br style="clear:both">
    <br style="clear:both">
    
    <div id="answerbuttongroup">
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
                ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="div-answer-answer-block" onclick="submitanswer('<?php echo strtoupper($ci);?>')">
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
    
    <script>
    function submitanswer(choose){
        clearInterval(timer1);
        $("#answerbuttongroup").css("display","none");
        $("#processinganimation").css("display","inline");
        
        $.ajax({
            url: "<?php echo PAGE_PATH;?>php/CAQ_function.php",
            type: "POST",
            data:{
                'what':'submitanswer',
                'choose':choose,
                'questionid':'<?php echo $questionid;?>'
            },
            success: function(data,status){
                data = JSON.parse(data);
                if(data.code == 0000){
                    $("#errortips").html(data.info);
                }else{
                    $("#errortips").html(data.info);
                    $("#errorbox").css("display","inline");
                    $("#answerbuttongroup").css("display","inline");
                    $("#processinganimation").css("display","none");
                }
            }
        });
    }
    </script>
    
    <div id="processinganimation" style="display:none;">
        <center>
            <h1><i class="fa fa-spinner fa-spin"></i></h1>
            <br>
            <h1>努力提交答案ing~</h1>
        </center>
    </div>
    
    <br>
    <br>
    <center>
        <p class="p-bottom-extras">科创社 2014 技术提供</p>
    </center>
</div>
<?php
}
?>











<?php
function showinfopage($appendix){
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div>
        <br style="clear:both">
        <center>
            <h1 style="font-size:72px;"><span class="<?php 
                list($belong,$info) = explode("::",$appendix);
                if($belong=="err"){
                    echo "glyphicon glyphicon-remove";
                }
                if($belong=="success"){
                    echo "glyphicon glyphicon-ok";
                }
                
                ?>"></span></h1>
            <h1><?php echo $info;?></h1>
        </center>
    </div>
    <br style="clear:both">
    <br style="clear:both">

    <button class="div-caq-button col-md-12 col-sm-12 col-xs-12" onclick="goidc()">←回到输入验证码的界面</button>
    <script>
    function goidc(){
        scanstate("idc");
    }
    </script>
    <br style="clear:both">
    <br style="clear:both">
    <br style="clear:both">
    <br style="clear:both">
    <br style="clear:both">
    <br style="clear:both">
    <br style="clear:both">
    <br style="clear:both">
    <center>
        <p class="p-bottom-extras">科创社 2014 技术提供</p>
    </center>
</div>
<?php
}
?>
