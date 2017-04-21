

<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">

    <!-- build:js /assets/js/admin/controller.js -->
    <script src="/front/assets/js/jquery-1.12.4.js"></script>
    <script src="/front/assets/js/bootstrap.js"></script>
    <!-- endbuild -->

    <!-- build:css /assets/css/admin/controller.css -->
    <link href="/front/assets/style/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="/front/assets/style/font-awesome.css">
    <!-- endbuild -->

    <script>
            var iosocket = io.connect("ws://localhost:8080");

            iosocket.on('connect', function () {
                console.log("连接成功!");
                
            });
        
    refreshdata();
        
    function refreshdata(){
        disableallbtn();
        $("#refreshbtn").attr("disabled",true);
        $("#refreshbtn").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;刷新上面的数据");
        var id=$("#opepanel-questionid").html();
        $("[sign-data='questionsign']").html("");
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/CAQ_function",
            type: "POST",
            data:{
                'what':'getAdminInfoPack',
                'questionid':id
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips").html(data.info);
                if(data.code == 0000){
                    $("#opepanel-questionid").html(data.currentquestionid);
                    $("#questionstate").html(data.questionstate);
                    $("#peopleoflimit").html(data.peoplelimit);
                    $("#peopleofjoined").html(data.peoplejoined);
                    $("#peopleofdone").html(data.peopledone);
                    $("#peopleoftimeout").html(data.peopletimeout);
                    $("#questiontitle").html(data.question);
                    $("#current-"+data.currentquestionid).html("当前");
                }
                setTimeout(clearTips,2000);
                $("#refreshbtn").html("刷新上面的数据");
                $("#refreshbtn").attr("disabled",false);
                enableallbtn();
            }
        });
    }
    
    //清除提示
    function clearTips(){
        $("#Operatetips").html("");
    }
        
    function disableallbtn(){
        $("#refreshbtn").attr("disabled",true);
        $("#showidcbtn").attr("disabled",true);
        $("#showquestionbtn").attr("disabled",true);
        $("#showkeybtn").attr("disabled",true);
        $("[sign-data='showsponsorbtn']").attr("disabled",true);
    }
        
    function enableallbtn(){
        $("#refreshbtn").attr("disabled",false);
        $("#showidcbtn").attr("disabled",false);
        $("#showquestionbtn").attr("disabled",false);
        $("#showkeybtn").attr("disabled",false);
        $("[sign-data='showsponsorbtn']").attr("disabled",false);
    }
        
    function showidc(){
        disableallbtn();
        $("#showidcbtn").attr("disabled",true);
        $("#showidcbtn").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;显示验证码");
        var id=$("#opepanel-questionid").html();
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/CAQ_function",
            type: "POST",
            data:{
                'what':'changequestionto',
                'questionid':id
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips").html(data.info);
                if(data.code == 0000){
                    iosocket.emit('showidc',{'questionid':id});
                    refreshdata();
                }
                setTimeout(clearTips,2000);
                $("#showidcbtn").html("显示验证码");
                $("#showidcbtn").attr("disabled",false);
                enableallbtn();
            }
        });
        
        
    }
        
    function showquestion(){
        disableallbtn();
        $("#showquestionbtn").attr("disabled",true);
        $("#showquestionbtn").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;显示题目");
        var id=$("#opepanel-questionid").html();
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/CAQ_function",
            type: "POST",
            data:{
                'what':'showquestion',
                'questionid':id
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips").html(data.info);
                if(data.code == 0000){
                    iosocket.emit('showquestion',{'questionid':id});
                    refreshdata();
                }
                setTimeout(clearTips,2000);
                $("#showquestionbtn").html("显示题目");
                $("#showquestionbtn").attr("disabled",false);
                enableallbtn();
            }
        });
    }
    
    function showkey(){
        disableallbtn();
        if($("#questionstate").html()=="showingkey"){
            $("#showkeybtn").attr("disabled",true);
            $("#showkeybtn").html("<span class=\"glyphicon glyphicon-remove\"></span>&nbsp;&nbsp;已经结算过了");
            $("#Operatetips").html("当前已经结算完毕!");
            setTimeout(clearTips,2000);
            setTimeout(function(){
                $("#showkeybtn").attr("disabled",false);
                $("#showkeybtn").html("显示答案+结算分数");
                enableallbtn();
            },2000);
            return;
        }
        
        $("#showkeybtn").attr("disabled",true);
        $("#showkeybtn").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;显示答案+结算分数");
        var id=$("#opepanel-questionid").html();
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/CAQ_function",
            type: "POST",
            data:{
                'what':'showkey',
                'questionid':id
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips").html(data.info);
                if(data.code == 0000){
                    iosocket.emit('showkey',{'questionid':id});
                    refreshdata();
                }
                setTimeout(clearTips,2000);
                $("#showkeybtn").html("显示答案+结算分数");
                $("#showkeybtn").attr("disabled",false);
                enableallbtn();
            }
        });
    }
        
    function showsponsor(id){
        $("#opepanel-questionid").html(id);
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/CAQ_function",
            type: "POST",
            data:{
                'what':'showsponser',
                'questionid':id
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips").html(data.info);
                if(data.code == 0000){
                    $("#Operatetips-"+id).html("成功切换!");
                    setTimeout(function(){
                        $("#Operatetips-"+id).html("");
                    },5000);
                    iosocket.emit('showsponsor',{'questionid':id});
                    refreshdata();
                }
                setTimeout(clearTips,2000);
            }
        });
    }
        
        
    function showprize(){
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/CAQ_function",
            type: "POST",
            data:{
                'what':'showprize',
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips").html(data.info);
                if(data.code == 0000){
                    iosocket.emit('showhonor',{'questionid':'nothing'});
                    refreshdata();
                }
                setTimeout(clearTips,2000);
            }
        });
    }
        
        
    function togglereging(changeto){
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/CAQ_function",
            type: "POST",
            data:{
                'what':'togglereging',
                'changeto':changeto
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips").html(data.info);
                if(data.code == 0000){
                    location.reload();
                }
                setTimeout(clearTips,2000);
            }
        });
    }
    </script>
</head>

<body id="maincontainer" style="background-color:#fafafa">
    <div class="col-md-12 col-sm-12 col-xs-12 div_mainblock">
        <br>
        <br>
        <?php
            $nc = new sqlhelper();
            $opecode = $nc->connect();
            if($opecode!=0){

            }
            $result = $nc ->mysql("select * from question order by sort");
            while($row = mysql_fetch_array($result)){
                
                $ncs = new sqlhelper();
                $opecodes = $ncs->connect();
                $results = $ncs->mysql("select * from question_config where keyname = 'currentquestionid'");
                $rows = mysql_fetch_array($results);
                if($rows['value'] == $row['id']){
                    $title = "(当前) ";
                }else{
                    $title = "";
                }
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <span id="current-<?php echo $row['sort'];?>" sign-data="questionsign"><?php echo $title;?></span>
                <span id="questionsort-<?php echo $row['sort'];?>"><?php echo $row['sort'].".";?></span>
                <span id="question-<?php echo $row['sort'];?>"><?php echo $row['question'];?></span>
            </div>
          <div class="panel-body">
            <?php
              if($title == "(当前) "){
                  $results = $ncs->mysql("select * from question_buffer where questionid = '{$row['id']}' and state='joined'");
                  $peoplehavegotincount = mysql_num_rows($results);
                  $results = $ncs->mysql("select * from question_buffer where questionid = '{$row['id']}' and state='done'");
                  $peoplehavedonecount = mysql_num_rows($results);
              ?>
              <?php
              }
            ?>
            
            <button sign-data="showsponsorbtn" class="col-md-3 col-sm-12 col-xs-12 btn btn-default btn-lg" onclick="showsponsor(<?php echo $row['id'];?>)">切换并显示赞助商</button>
            <p id="Operatetips-<?php echo $row['id'];?>"></p>
          </div>
        </div>
        <br style="clear:both">
        <?php
            }
        ?>
        
        <br style="clear:both">
        <button class="col-md-3 col-sm-12 col-xs-12 btn btn-default btn-lg" onclick="showprize()">显示获奖名单</button>
        
        <br style="clear:both">
        <br style="clear:both">
        <?php
        $result = $nc ->mysql("select value from question_config where keyname='isreging'");
        $row = mysql_fetch_array($result);
        if($row['value']=='true'){
        ?>
            <button class="col-md-3 col-sm-12 col-xs-12 btn btn-default btn-lg" onclick="togglereging('false')">关闭注册</button>
        <?php
        }else{
        ?>
            <button class="col-md-3 col-sm-12 col-xs-12 btn btn-default btn-lg" onclick="togglereging('true')">开放注册</button>
        <?php
        }
        ?>
        <br style="clear:both">
        <br style="clear:both">
        <br style="clear:both">
        <br style="clear:both">
        
        
        <nav class="navbar navbar-default navbar-fixed-bottom">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">打开面板</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><span id="questiontitle"></span></a>
                </div>

                <!-- 合起来里面的东西 -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <p> 问题id:<span id="opepanel-questionid">1</span><br>
                        当前问题状态:&nbsp;&nbsp;<span id="questionstate"></span>
                    </p>
                    <p>问题允许人数:&nbsp;&nbsp;<span id="peopleoflimit"></span><br>
                        问题进入人数:&nbsp;&nbsp;<span id="peopleofjoined"></span><br>
                        问题完成人数:&nbsp;&nbsp;<span id="peopleofdone"></span><br>
                        答题超时人数:&nbsp;&nbsp;<span id="peopleoftimeout"></span>
                    </p>
                    <button type="button" id="refreshbtn" class="btn btn-default navbar-btn col-md-3 col-sm-12 col-xs-12" onclick="refreshdata()">刷新上面的数据</button>
                    <br style="clear:both">
                    <p id="Operatetips"></p>
                    <br style="clear:both">
                    <button type="button" id="showidcbtn" class="btn btn-default btn-lg navbar-btn col-md-3 col-sm-12 col-xs-12" onclick="showidc()">显示验证码</button>
                    <br style="clear:both">
                    <button type="button" id="showquestionbtn" class="btn btn-default btn-lg navbar-btn col-md-3 col-sm-12 col-xs-12" onclick="showquestion()">显示题目</button>
                    <br style="clear:both">
                    <button type="button" id="showkeybtn" class="btn btn-default btn-lg navbar-btn col-md-3 col-sm-12 col-xs-12" onclick="showkey()">显示答案+结算分数</button>
                    <br style="clear:both">
                </div>
            </div>
        </nav>
    </div>
</body>

</html>