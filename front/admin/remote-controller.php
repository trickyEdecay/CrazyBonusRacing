<?php
$nc = new sqlihelper();
if(!$nc->connect()){
}
$result = $nc ->mysql("select * from question_config where keyname = 'currentquestionid' limit 1");
$row = $result->fetch_assoc();
$currentQuestionId = $row['value'];
?>

<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">

    <!-- build:js /assets/js/admin/controller.js -->
    <script src="/front/assets/js/jquery-1.12.4.js"></script>
    <script src="/front/assets/js/socket.io-1-7-3.js"></script>
    <!-- endbuild -->

    <!-- build:css /assets/css/admin/controller.css -->
    <link rel="stylesheet" href="/front/assets/style/font-awesome.css">
    <link href="/front/assets/style/admin/remote-controller.less" rel="stylesheet">
    <!-- endbuild -->

    <script>
        var iosocket = io.connect("ws://localhost:8080");
        var questionState = "";
        var currentQuestionId = <?php echo $currentQuestionId ?>;
        iosocket.on('connect', function () {
            console.log("连接成功!");

        });


    refreshdata();
        
    function refreshdata(){
        if(isAllBtnDisabled){
            return;
        }
        disableallbtn();
        $("#refreshbtn").attr("disabled",true);
        $("#refreshbtn").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;刷新上面的数据");
        $("[sign-data='questionsign']").html("");
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/remote-control",
            type: "POST",
            data:{
                'what':'getQuestionStatus',
                'questionid':currentQuestionId
            },
            success: function(data,status){
                data = JSON.parse(data);
                showTips(data.info);
                if(data.code == 0000){
                    currentQuestionId = data['currentquestionid'];
                    questionState = data['questionstate'];
                    $("#questionstate").html(data.questionstate);

                    decideToShowToolBtns();

                    $("#peopleoflimit").html(data.peoplelimit);
                    $("#playerJoined").html(data.peoplejoined);
                    $("#playerCount").html(data['playerCount']);
                    $("#playerDone").html(data.peopledone);
                    $("#playerTimeout").html(data.peopletimeout);
                    $("#questiontitle").html(data.question);
                    $("#current-"+data.currentquestionid).html("当前");

                    $("#pb_joined").css("width",Math.round(data['peoplejoined']/data['playerCount']*100)+"%");
                    $("#pb_done").css("width",Math.round(data['peopledone']/data['playerCount']*100)+"%");
                    $("#pb_timeout").css("width",Math.round(data['peopletimeout']/data['playerCount']*100)+"%");
                }
                $("#refreshbtn").html("刷新上面的数据");
                $("#refreshbtn").attr("disabled",false);
                enableallbtn();
            }
        });
    }


    //显示提示
    function showTips(info){
        $("#opeTips").html(info);
        $("#opeTips").addClass("show");
        setTimeout(function(){
            $("#opeTips").removeClass("show");
        },2500);
    }
    var isAllBtnDisabled = false;
    var isAllToolBtnShown = false;
    function disableallbtn(){
        $(".btn").addClass('disabled');
        isAllBtnDisabled = true;
    }
        
    function enableallbtn(){
        $(".btn").removeClass('disabled');
        isAllBtnDisabled = false;
    }


    //显示所有工具按钮
    function toggleAllToolBtns() {
        if(!isAllToolBtnShown){
            $("#showAllToolToggle").html("仅显示推荐工具按钮");
            isAllToolBtnShown = true;
            $(".btn-middle").css('display','inline-block');
        }else{
            $("#showAllToolToggle").html("显示所有工具按钮");
            isAllToolBtnShown = false;
            refreshdata();
        }

    }

    //决定显示哪个工具按钮
    function decideToShowToolBtns(){
        if(!isAllToolBtnShown){
            $(".btn-middle").css('display','none');
            $(".btn-middle").children(".mask").css('width','100%');
            switch(questionState){
                case 'showingSponsor':
                    $("#showCaptchaBtn").css('display','inline-block');
                    break;
                case 'showingCaptcha':
                    $("#showQuestionBtn").css('display','inline-block');
                    break;
                case 'showingQuestion':
                    $("#showSolutionBtn").css('display','inline-block');
                    break;
                case 'showingSolution':
                    $("#goNextSponsorBtn").css('display','inline-block');
                    break;
            }
            var timer;
            var second = 0;
            timer = setInterval(function(){
                if(second>=2000){
                    $(".btn-middle").children(".mask").css('width',"0%");
                    clearInterval(timer);
                }else{
                    $(".btn-middle").children(".mask").css('width',(2000-second)/2000*100+"%");
                    second+=10;
                }
            },10);
        }
    }
        
    function showCaptcha(){
        if(isAllBtnDisabled){
            return;
        }else if(!isAllToolBtnShown && $(".btn-middle").children(".mask").width() > 0){
            return
        }
        disableallbtn();
        $("#showidcbtn").attr("disabled",true);
        $("#showidcbtn").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;显示验证码");
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/remote-control",
            type: "POST",
            data:{
                'what':'showCaptcha'
            },
            success: function(data,status){
                data = JSON.parse(data);
                showTips(data.info);
                if(data.code == 0000){
                    iosocket.emit('showidc',{'questionid':currentQuestionId});
                }
                questionState = 'showingCaptcha';
                $("#showidcbtn").html("显示验证码");
                decideToShowToolBtns();
                enableallbtn();
                setTimeout(refreshdata(),1000);
            }
        });
        
        
    }
        
    function showquestion(){
        if(isAllBtnDisabled){
            return;
        }else if(!isAllToolBtnShown && $(".btn-middle").children(".mask").width() > 0){
            return
        }
        disableallbtn();
        $("#showquestionbtn").attr("disabled",true);
        $("#showquestionbtn").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;显示题目");
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/remote-control",
            type: "POST",
            data:{
                'what':'showQuestion'
            },
            success: function(data,status){
                data = JSON.parse(data);
                showTips(data.info);
                if(data.code == 0000){
                    iosocket.emit('showquestion',{'questionid':currentQuestionId});
                }
                questionState = 'showingQuestion';
                $("#showquestionbtn").html("显示题目");
                decideToShowToolBtns();
                enableallbtn();
                setTimeout(refreshdata(),1000);
            }
        });
    }
    
    function showkey(){
        if(isAllBtnDisabled){
            return;
        }else if(!isAllToolBtnShown && $(".btn-middle").children(".mask").width() > 0){
            return
        }
        disableallbtn();
        if($("#questionstate").html()=="showingSolution"){
            $("#showkeybtn").attr("disabled",true);
            $("#showkeybtn").html("<span class=\"glyphicon glyphicon-remove\"></span>&nbsp;&nbsp;已经结算过了");
            showTips("当前已经结算完毕");
            setTimeout(function(){
                $("#showkeybtn").html("显示答案+结算分数");
                enableallbtn();
            },2000);
            return;
        }

        $("#showkeybtn").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;显示答案+结算分数");
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/remote-control",
            type: "POST",
            data:{
                'what':'showSolution'
            },
            success: function(data,status){
                data = JSON.parse(data);
                showTips(data.info);
                if(data.code == 0000){
                    iosocket.emit('showkey',{'questionid':currentQuestionId});
                }
                questionState = 'showingSolution';
                $("#showkeybtn").html("显示答案+结算分数");
                decideToShowToolBtns();
                enableallbtn();
                setTimeout(refreshdata(),1000);
            }
        });
    }

    //跳到下一个问题
    function goNextSponsor(){
        var nextEle = $(".question-panel[data-id='"+currentQuestionId+"']").next();
        if(nextEle.hasClass('question-panel')){
            var nextId = nextEle.attr('data-id');
            showsponsor(nextId);
        }
    }
        
    function showsponsor(id){
        $(".question-panel[data-id='"+currentQuestionId+"']").removeClass("current");
        currentQuestionId = id;
        $(".question-panel[data-id='"+currentQuestionId+"']").addClass("current");
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/remote-control",
            type: "POST",
            data:{
                'what':'showSponsor',
                'questionId':currentQuestionId
            },
            success: function(data,status){
                data = JSON.parse(data);
                showTips(data.info);
                if(data.code == 0000){
                    showTips("成功切换");
                    iosocket.emit('showsponsor',{'questionid':currentQuestionId});
                    refreshdata();
                }
            }
        });
    }
        
        
    function showWinners(){
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/remote-control",
            type: "POST",
            data:{
                'what':'showWinners',
            },
            success: function(data,status){
                data = JSON.parse(data);
                showTips(data.info);
                if(data.code == 0000){
                    iosocket.emit('showhonor',{'questionid':'nothing'});
                    refreshdata();
                }
            }
        });
    }
        
        
    function toggleReging(changeTo){
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/remote-control",
            type: "POST",
            data:{
                'what':'toggleReging',
                'changeTo':changeTo
            },
            success: function(data,status){
                data = JSON.parse(data);
                showTips(data.info);
                if(data.code == 0000){
                    location.reload();
                }
            }
        });
    }


    $(function(){

        $("#expandBtn").click(function(){
            if($("#bottomContent").hasClass("show")){
                $("#bottomContent").removeClass("show");
            }else{
                $("#bottomContent").addClass("show");
            }
        });

        refreshdata();
    });
    </script>
</head>

<body>
<div id="opeTips"></div>
        <br>
        <br>
        <?php
            $result = $nc ->mysql("select * from question order by sort");
            while($row = $result->fetch_assoc()){
        ?>
                <div class="question-panel <?php echo $currentQuestionId==$row['id']?'current':''; ?>" data-id="<?php echo $row['id'];?>">
                    <div class="title">
                        <div class="current">当前</div>
                        <h1><?php echo $row['sort'].".".$row['question'];?></h1>
                    </div>
                    <div class="content">
                        <div class="btn" onclick="showsponsor(<?php echo $row['id'];?>)">切换并显示赞助商</div>

                    </div>
                </div>
        <?php
            }
        ?>


        <div class="btn btn-outer green" onclick="showWinners()">显示获奖名单</div>

        <?php
        $result = $nc ->mysql("select value from question_config where keyname='isreging'");
        $row = $result->fetch_assoc();
        if($row['value']=='true'){
        ?>
            <div class="btn btn-outer red" onclick="toggleReging(false)">关闭注册</div>
        <?php
        }else{
        ?>
            <div class="btn btn-outer green" onclick="toggleReging(true)">开放注册</div>
        <?php
        }
        $nc->close();
        ?>
        <div id="showAllToolToggle" class="btn btn-outer yellow" onclick="toggleAllToolBtns()">显示所有工具按钮</div>

<!--底部的工具栏-->
        <div class="bottom-toolbar">
            <div class="title">
                <div id="expandBtn"><span class="fa fa-expand"></span></div>
                <h1 id="questiontitle"></h1>
            </div>
            <div class="content" id="bottomContent">

                <div class="state-display">
                    <div class="small">问题状态</div>
                    <h1 id="questionstate"></h1>
                    <div class="player-data">
                        <div class="data-line">
                            <span>游戏总人数：</span><span class="statics" id="playerCount">12</span><span>人</span>
                        </div>
                        <div class="data-item">
                            <span>正在作题：</span><span class="statics" id="playerJoined">12</span><span>人</span>
                        </div>
                        <div class="data-item">
                            <span>完成答题：</span><span class="statics" id="playerDone">12</span><span>人</span>
                        </div>
                        <div class="data-item">
                            <span>答题超时：</span><span class="statics" id="playerTimeout">12</span><span>人</span>
                        </div>
                    </div>
<!--                    进度条形式的信息显示-->
                    <div class="progress-bar-bg">
                        <div class="progress-bar yellow" id="pb_joined"></div>
                        <div class="progress-bar green" id="pb_done"></div>
                        <div class="progress-bar red" id="pb_timeout"></div>
                    </div>

                    <div id="refreshBtn" onclick="refreshdata()"><span class="fa fa-refresh"></span></div>
                </div>
                <div id="showCaptchaBtn" class="btn btn-middle green" onclick="showCaptcha()"><div class="word">显示验证码</div><div class="mask"></div></div>
                <div id="showQuestionBtn" class="btn btn-middle green" onclick="showquestion()"><div class="word">显示题目</div><div class="mask"></div></div>
                <div id="showSolutionBtn" class="btn btn-middle green" onclick="showkey()"><div class="word">显示答案+结算分数</div><div class="mask"></div></div>
                <div id="goNextSponsorBtn" class="btn btn-middle green" onclick="goNextSponsor()"><div class="word">切换到下一题</div><div class="mask"></div></div>
            </div>
        </div>

        <div class="bottom-placeholder"></div>
</body>

</html>