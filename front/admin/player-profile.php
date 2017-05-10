

<?php
if(is_array($_GET)&&count($_GET)>0){ 
    if(isset($_GET["t"])){ 
        $t=$_GET["t"];//存在 
    }else{
        $t="";
    }
}else{
    $t="";
}


$nc = new sqlihelper();
if(!$nc->connect()){
    $err->{'code'} = 0001;
    $err->{'info'} = "连接错误";
    die(json_encode($err));
}
$result = $nc ->mysql("select * from question_people where id=$t limit 1");
if($result->num_rows == 0){
    die("资料库中找不到资料!");
}
$row = $result->fetch_assoc();

$peopleid = $t;
$name = $row['name'];
$tel = $row['tel'];
$rightcount = $row['rightcount'];
$wrongcount = $row['wrongcount'];
$score = $row['score'];
$historyscore = $row['historyscore'];
$ranking = $row['ranking'];
$historyranking = $row['historyranking'];
$rightids = $row['rightids'];
$wrongids = $row['wrongids'];
$achievetime = $row['achievetime'];
$achievets = $row['achievets'];
$isbanned = $row['isbanned'];
$wrongidccount = $row['wrongidccount'];
$participateyears = $row['participateyears'];
$active = $row['active'];
$activeminusscore = $row['activeminusscore'];
$lastactiveyear = $row['lastactiveyear'];

$answertimes = count(explode(",",$historyscore))-1;

$rightidsarray = array_filter(array_unique(explode(",",$rightids)));
$wrongidsarray = array_filter(array_unique(explode(",",$wrongids)));

$correctrate = $rightcount+$wrongcount == 0? 100 : round($rightcount/($rightcount+$wrongcount)*100);

$delays = "";
$result = $nc ->mysql("select delay from question_idcinputtime where peopleid=$t");
while($row = $result->fetch_assoc()){
    $delays = $delays.$row['delay'].",";
}
$delayarray = array_filter(explode(",",$delays));


?>


<!DOCTYPE html>
<html>

    <head>
        <title>玩家数据管理</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <!-- build:js /assets/js/player-profile.js -->
        <script src="/front/assets/js/jquery-1.12.4.js"></script>
        <script src="/front/assets/js/bootstrap.js"></script>
        <script src="/front/assets/js/chartist.js"></script>
        <!-- endbuild -->

        <!-- build:css /assets/css/player-profile.css -->
        <link href="/front/assets/style/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" href="/front/assets/style/font-awesome.css">
        <link rel="stylesheet" href="/front/assets/style/chartist.css">
        <link rel="stylesheet" href="/front/assets/style/admin/player-manage.less">
        <!-- endbuild -->



    </head>
    <body>
        <div class="container">
            <div class="page-header">
                <h1><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;<?php echo $name;?>&nbsp;&nbsp;&nbsp;&nbsp;<small><span class="glyphicon glyphicon-phone"></span>&nbsp;<?php echo $tel;?></small></h1>
            </div>
            
            <?php
            if($isbanned==1){
            ?>
            <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span>此账号已经被封禁
                <button type="button" class="btn btn-danger btn-xs" style="float:right" onclick="deban()">解封</button>
            </div>
            <?php
            }
            ?>
            
            
            <?php
            if($wrongidccount >0){
            ?>
            <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span>此账号已经输错验证码<strong><?php echo $wrongidccount;?></strong>次了
                <button type="button" class="btn btn-warning btn-xs" style="float:right" onclick="clearWrongCaptchaCount()">清除次数</button>
            </div>
            <?php
            }
            ?>
            
            
            <br>
            <br>
            <p>&nbsp;<span class="glyphicon glyphicon-time"></span>&nbsp;&nbsp;最后参与活动年份:&nbsp;<?php echo $lastactiveyear;?></p>
            <p>&nbsp;<span class="glyphicon glyphicon-time"></span>&nbsp;&nbsp;分数达成时间:&nbsp;<?php echo $achievetime."(".$achievets."ms)"?></p>
            <div class="page-header">
                <h3><span class="glyphicon glyphicon-yen"></span>&nbsp;分数变化情况&nbsp;<small>最终分数:&nbsp;<?php echo $score;?>分</small></h3>
            </div>
            <div id="scorechart" style="position:relative">
                <div class="cttooltip cttooltip-hidden" id="score-tooltip"></div>
            </div>
            
            
            
            <br>
            <br>
            <div class="page-header">
                <h3><span class="glyphicon glyphicon-sort"></span>&nbsp;排名变化情况&nbsp;<small>最终排名:&nbsp;<?php echo $ranking;?></small></h3>
            </div>
            <div id="rankingchart" style="position:relative">
                <div class="cttooltip cttooltip-hidden" id="ranking-tooltip"></div>
            </div>
            
            <br>
            <br>
            <br>
            
            
            
            <br>
            <br>
            <div class="page-header">
                <h3><span class="glyphicon glyphicon-random"></span>&nbsp;答题时间延迟变化情况&nbsp;</h3>
            </div>
            <div id="delaychart" style="position:relative">
                <div class="cttooltip cttooltip-hidden" id="delay-tooltip"></div>
            </div>
            <?php
            if($active >1){
            ?>
            <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;此账号有消极答题的嫌疑,至少<strong><?php echo $active;?></strong>次了
                <button type="button" class="btn btn-warning btn-xs" style="float:right" onclick="clearactivecount()">清除次数</button>
            </div>
            <?php
            }
            ?>
            <div class="alert alert-info" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;这个人因为消极答题已经被扣了<strong><?php echo $activeminusscore;?></strong>分了
                <button type="button" class="btn btn-danger btn-xs" style="float:right" onclick="passive()">判定为消极作答</button>
            </div>
            
            <br>
            <br>
            <br>
            
            
            <div class="page-header">
                <h3><span class="glyphicon glyphicon-th-list"></span>&nbsp;答题情况&nbsp;<small>准确率:&nbsp;<?php echo $correctrate;?>%</small></h3>
            </div>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                  <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      <span class="glyphicon glyphicon-ok"></span>&nbsp;答对的题目(<?php echo count($rightidsarray);?>题)
                    </a>
                  </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                  <div class="panel-body">
                    <ul>
                        <?php
                        foreach($rightidsarray as $v){
                            $result = $nc ->mysql("select question from question where id={$v} limit 1");
                            $row = $result->fetch_assoc();
                            echo "<li>".$row['question']."</li>";
                        }  
                        ?>
                      </ul>
                  </div>
                </div>
              </div>
            
                
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                  <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      <span class="glyphicon glyphicon-remove"></span>&nbsp;答错的题目(<?php echo count($wrongidsarray);?>题)
                    </a>
                  </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                  <div class="panel-body">
                      <ul>
                        <?php
                        foreach($wrongidsarray as $v){
                            $result = $nc ->mysql("select question from question where id={$v} limit 1");
                            $row = $result->fetch_assoc();
                            echo "<li>".$row['question']."</li>";
                        }  
                        ?>
                      </ul>
                  </div>
                </div>
              </div>
                
            </div>
            
            
            
        </div>
        
        
        
        
        <div class="modal fade" id="confirmmodal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="modaltitle">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        <p id="modalcontent">One fine body&hellip;</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="button" class="btn btn-primary" onclick="sendope()">确定</button>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        
        <script>
            var opetype = "";
            var peopleid = 0;
                //解封账号
            function deban(id){
                opetype = "deban";
                peopleid = id;
                $("#confirmmodal").modal('show');
                $("#modaltitle").html('解封确认');
                $("#modalcontent").html('确定要解封&nbsp;&nbsp;<strong><?php echo $name;?><small>(<?php echo $tel;?>)</small></strong>&nbsp;&nbsp;吗?');
            }
            
            function passive(id){
                opetype = "passive";
                peopleid = id;
                $("#confirmmodal").modal('show');
                $("#modaltitle").html('判定确认');
                $("#modalcontent").html('确定要判定&nbsp;&nbsp;<strong><?php echo $name;?><small>(<?php echo $tel;?>)</small></strong>&nbsp;&nbsp;为消极作答吗?');
            }
            function clearWrongCaptchaCount(id){
                opetype = "clearWrongCaptchaCount";
                peopleid = id;
                $("#confirmmodal").modal('show');
                $("#modaltitle").html('判定确认');
                $("#modalcontent").html('确定要清零&nbsp;&nbsp;<strong><?php echo $name;?><small>(<?php echo $tel;?>)</small></strong>&nbsp;&nbsp;的验证码输错次数吗?');
            }
            
            function clearactivecount(id){
                opetype = "clearactive";
                peopleid = id;
                $("#confirmmodal").modal('show');
                $("#modaltitle").html('清空消极答题嫌疑');
                $("#modalcontent").html('确定要清空&nbsp;&nbsp;<strong><?php echo $name;?><small>(<?php echo $tel;?>)</small></strong>&nbsp;&nbsp;的消极游戏嫌疑吗?');
            }
            
            
            
            function sendope(){
                $.ajax({
                    url: "<?php echo ROOT_PREFIX.API;?>/manage-playerdata",
                    type: "POST",
                    data:{
                        'what': opetype,
                        'peopleid': <?php echo $peopleid; ?>
                    },
                    success: function(data,status){
                        data = JSON.parse(data);
                        $("#Operatetips").html(data.info);
                        if(data.code == 0000){
                            location.reload();
                        }
                    }
                });
            }
                       
                       
                       
                       
            //搞个tooltip出来
           $(document).ready(function(){
                $('[data-toggle=tooltip]').mouseover(function() { 
                    $(this).tooltip({
                        container:'body',
                        trigger:'click hover focus'
                    }); 
                    $(this).tooltip('show'); 
                })    
            });
                
                
                
                
                //分数变化的图表建立
                new Chartist.Line('#scorechart', {
                  labels: [<?php for($i=1;$i<$answertimes;$i++){echo $i.',';}?>],
                  series: [
                    {
                      name: 'Fibonacci sequence',
                      data: [<?php echo $historyscore;?>]
                    }
                  ]
                });


                var $chart = $('#scorechart');

                $chart.on('mouseenter',".ct-point", function() {
                  var $point = $(this);
                  $point.animate({'stroke-width': '25px'}, 300);
                    //tooltip内容设置
                    var value = $(this).attr('ct:value');
                  $('#score-tooltip').html(value+"分");
                  $('#score-tooltip').removeClass('cttooltip-hidden');
                });

                $chart.on('mouseleave',".ct-point", function() {
                  var $point = $(this);

                  $point.animate({'stroke-width': '10px'}, 300);
                  $('#score-tooltip').addClass('cttooltip-hidden');
                });

                $chart.on('mousemove', function(event) {
                  $('#score-tooltip').css({
                    left: (event.offsetX || event.originalEvent.layerX) - $('#score-tooltip').width() / 2,
                    top: (event.offsetY || event.originalEvent.layerY) - $('#score-tooltip').height() - 20
                  });
                });
                
                
                
                
                
                
                
                //排名变化的图表建立
                new Chartist.Line('#rankingchart', {
                  labels: [<?php for($i=1;$i<$answertimes;$i++){echo $i.',';}?>],
                  series: [
                    {
                      name: 'Fibonacci sequence',
                      data: [<?php echo $historyranking;?>]
                    }
                  ]
                });


                var $chart = $('#rankingchart');

                $chart.on('mouseenter',".ct-point", function() {
                  var $point = $(this);
                  $point.animate({'stroke-width': '25px'}, 300);
                    //tooltip内容设置
                    var value = $(this).attr('ct:value');
                  $('#ranking-tooltip').html(value);
                  $('#ranking-tooltip').removeClass('cttooltip-hidden');
                });

                $chart.on('mouseleave',".ct-point", function() {
                  var $point = $(this);

                  $point.animate({'stroke-width': '10px'}, 300);
                  $('#ranking-tooltip').addClass('cttooltip-hidden');
                });

                $chart.on('mousemove', function(event) {
                  $('#ranking-tooltip').css({
                    left: (event.offsetX || event.originalEvent.layerX) - $('#ranking-tooltip').width() / 2,
                    top: (event.offsetY || event.originalEvent.layerY) - $('#ranking-tooltip').height() - 20
                  });
                });
                
                
                
                
                
                
                
                //延迟变化的图表建立
                new Chartist.Line('#delaychart', {
                  labels: [<?php for($i=1;$i<count($delayarray);$i++){echo $i.',';}?>],
                  series: [
                    {
                      name: 'Fibonacci sequence',
                      data: [<?php echo $delays;?>]
                    }
                  ]
                });


                var $chart = $('#delaychart');

                $chart.on('mouseenter',".ct-point", function() {
                  var $point = $(this);
                  $point.animate({'stroke-width': '25px'}, 300);
                    //tooltip内容设置
                    var value = $(this).attr('ct:value');
                  $('#delay-tooltip').html(value);
                  $('#delay-tooltip').removeClass('cttooltip-hidden');
                });

                $chart.on('mouseleave',".ct-point", function() {
                  var $point = $(this);

                  $point.animate({'stroke-width': '10px'}, 300);
                  $('#delay-tooltip').addClass('cttooltip-hidden');
                });

                $chart.on('mousemove', function(event) {
                  $('#delay-tooltip').css({
                    left: (event.offsetX || event.originalEvent.layerX) - $('#delay-tooltip').width() / 2,
                    top: (event.offsetY || event.originalEvent.layerY) - $('#delay-tooltip').height() - 20
                  });
                });
        </script>
    </body>
</html>

<?php
$nc->close();
?>