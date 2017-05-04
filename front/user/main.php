<?php
//验证在线
require_once("login.php");
$err = getLoginState();
$err= json_decode($err,true);
if($err['code']!=0000){
    $tip = $err['info'];
    require_once (PAGES."/user/login.php");
    die();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>全民答疯抢</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">

        <!-- build:js /assets/js/user/main.js -->
        <script src="/front/assets/js/jquery-1.12.4.js"></script>
        <script src="/front/assets/js/bootstrap.js"></script>
        <script src="/front/assets/js/jquery.easing.compatibility.1.4.1.js"></script>
        <script src="/front/assets/js/jquery.animateNumber.min.js"></script>
        <!-- endbuild -->

        <!-- build:css /assets/css/user/main.css -->
        <link href="/front/assets/style/bootstrap.css" rel="stylesheet">
        <link href="/front/assets/style/font-awesome.css" rel="stylesheet">
        <link href="/front/assets/style/user/main.less" rel="stylesheet">
        <!-- endbuild -->

    </head>

    <body id="maincontainer">
        
        <!--等待输入验证码页面-->
        <section id="captchaPanel">
            <header>
                <img id="logo" src="/front/assets/img/caq_logo_white.png">
                <br>
                <img id="ad" src="/front/assets/img/banner_ad.png">
            </header>

<!--            个人信息-->
            <div id="profile">
                <div class="color-block rank-container">
                    <h4>排名</h4>
                    <p id="rank">1</p>
                </div>
                <div class="color-block score-container">
                    <h4>分数</h4>
                    <p id="score">1</p>
                </div>
                <div class="name-block">
                    <h1 id="playerName">loading..</h1>
                    <div id="ranking-icon" data-type="up"></div>
                </div>
                <div class="data-block">
                    <p>
                        <i class="fa fa-check" aria-hidden="true"></i>&nbsp;准确率:
                        <span id="correctRate">100%</span>
                    </p>
                    <p>
                        <i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;分数达成时间:
                        <span id="achieveTime">loading..</span>
                    </p>
                </div>
                <button class="refresh" id="refresh"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;刷新</button>
            </div>
<!--            个人信息完-->

            <br>
            <div class="div-alert-info" id="ban-info"><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;&nbsp;很抱歉,您已经连续输入五次错误的验证码,您的账号已经被封禁,请向现场工作人员求助,带来不便敬请谅解</div>
            
            <div class="div-alert-info" id="active-info"><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;&nbsp;哈喽？您还在进行活动吗？如果您一直挂机，系统将扣除您的一定分数哦。</div>

            <br>
            <br>
            <div class="container" id="oldcomertip" style="display:none;">
                <div class="alert alert-success" role="alert">
                    <strong>·&nbsp;勇士归来&nbsp;·</strong><br><br>
                    欢迎回来!去年参与过比赛的勇士啊~<br><br>为了呈现一场高质量的大学活动~我们在各个方面都做了很多巨大改进<br><br>谢谢你能来见证我们的成长!<br>愿你在今年的比赛里取得更加优异的成绩!
                    <br>
                    <br>
                    <button type="button" class="btn btn-success" id="OldComercopythat">我知道啦~</button>
                </div>
            </div>
<!--            验证码提交表单-->
            <form id="captcha-form">
                <div class="input_group">
                    <input id="captcha" placeholder="请输入验证码" type="number" tabindex="1">
                </div>
                <button class="submit_btn" id="goAnswer" type="submit">进入疯狂抢答</button>
            </form>
            <p id="checkidctips" class="p-error"></p>
            <br style="clear:both">
            <br>
            <br>
            <br>
            <br>
            <p class="p-bottom-extras">科创社·科技部 技术提供</p>

        </section>
        
        <!--答题面板-->
        <section id="answerpanel" style="display:none">
            <div class="container-fluid" style="padding:0;">
                <br>
                <div class="col-md-1 col-sm-1 col-xs-1">
                    <div class="div-answer-index-circle"><div class="div-answer-index-number" id="answerpanelranking">N</div></div>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <span class="div-answer-mobile-name" id="answerpanelname">&nbsp;&nbsp;loading</span><span class="div-answer-mobile-score" id="answerpanelscore">loading..</span>
                </div>
                <div class="col-md-1 col-sm-1 col-xs-1" style="padding-left: 0px;">
                    <div class="div-answer-addscore-block" id="addscore">0</div>
                </div>
                <div class="col-md-1 col-sm-1 col-xs-1">
                    <div class="div-answer-minusscore-block" id="minusscore">-0</div>
                </div>
                <br style="clear:both">
                <br style="clear:both">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="div-answer-question-block" id="question">loading</div>
                </div>

                <br style="clear:both">
                <br style="clear:both">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="timebaricon"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
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
                
                </script>

                <br style="clear:both">
                <br style="clear:both">

                <div id="answerbuttongroup">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="div-answer-answer-block" onclick="submitSolution('A')">
                            <span id="answer-a"></span>
                            <br style="clear:both">
                        </div>
                    </div>
                    <br style="clear:both">
                    <br style="clear:both">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="div-answer-answer-block" onclick="submitSolution('B')">
                            <span id="answer-b"></span>
                            <br style="clear:both">
                        </div>
                    </div>
                    <br style="clear:both">
                    <br style="clear:both">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="div-answer-answer-block" onclick="submitSolution('C')">
                            <span id="answer-c"></span>
                            <br style="clear:both">
                        </div>
                    </div>
                    <br style="clear:both">
                    <br style="clear:both">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="div-answer-answer-block" onclick="submitSolution('D')">
                            <span id="answer-d"></span>
                            <br style="clear:both">
                        </div>
                    </div>
                    <br style="clear:both">
                    <br style="clear:both">
                    
                    
                </div>

                <script>
                
                </script>

                <div id="processinganimation" style="display:none;">
                    <center>
                        <h1><i class="fa fa-spinner fa-spin"></i></h1>
                        <br>
                        <h1 class="allinfo">努力提交答案ing~</h1>
                    </center>
                </div>

                <br>
                <br>
                <center>
                    <p class="p-bottom-extras">科创社·科技部 技术提供</p>
                </center>
            </div>
        </section>
        
        
        <!--提示信息页面-->
        <section id="infopanel" style="display:none">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div>
                    <br style="clear:both">
                    <center>
                        <h1 style="font-size:72px;"><span class="glyphicon glyphicon-ok" id="infopanel-icon"></span></h1>
                        <h1 id="infopanel-info">loading</h1>
                    </center>
                </div>
                <br style="clear:both">
                <br style="clear:both">

                <button class="div-caq-button col-md-12 col-sm-12 col-xs-12" onclick="scanstate('idc')"><span class="glyphicon glyphicon-circle-arrow-left"></span>&nbsp;&nbsp;回到输入验证码的界面</button>
                <br style="clear:both">
                <br style="clear:both">
                <br style="clear:both">
                <br style="clear:both">
                <br style="clear:both">
                <br style="clear:both">
                <br style="clear:both">
                <br style="clear:both">
                <center>
                    <p class="p-bottom-extras">科创社·科技部 技术提供</p>
                </center>
            </div>
        </section>
        
        
        
        
        
        
        
        <script>
            
            var questionid = 0;
            var availabletime = 10; //允许的答题时间
            var startAnswerTime = 0; //答题的开始时间
            var timer1;
            
            
            
            $(document).ready(function(){
                scanstate("idc");
            });

            //保留scanstate和老版本兼容
            function scanstate(what,appendix){
                appendix = appendix||"";
                
                switch(what){
                    case "idc":
                        showidcpanel();
                        break;
                    case "answer":
                        showanswerpanel();
                        break;
                    case "infopage":
                        showinfopanel(appendix);
                        break;
                }
            }
            
            
            function getDataFromServer(what,callback){
                var result = {};
                result.err = 1000;
                result.errinfo = "QAQ为什么服务器要和我分手!!";
                $.ajax({
                    url: "<?php echo ROOT_PREFIX.API;?>/mobile",
                    type: "POST",
                    data:{
                        'what':what
                    },
                    error:function(){
                        callback(result);
                    },
                    success: function(data,status){
                        result = JSON.parse(data);
                        callback(result);
                    }
                });
                
            }
            
            
            function showidcpanel(){
                $("#captchaPanel").css("display","none");
                $("#answerpanel").css("display","none");
                $("#infopanel").css("display","none");
                
                
                var idcpanelpack = {};
                getDataFromServer("getProfile",function(idcpanelpack){
                    if(idcpanelpack.err != 0000){
                        $("#checkidctips").html(idcpanelpack.errinfo);
                        $("#captchaPanel").css("display","block");
                        return;
                    }

                    //绑定数据
                    $('#captcha').val("");
//                    $("#rank").html(idcpanelpack.ranking);
                    $("#answerpanelranking").html(idcpanelpack.ranking);
                    $("#playerName").html(idcpanelpack.name);
                    $("#answerpanelname").html(idcpanelpack.name);
                    $("#answerpanelscore").html("  (你的总分: "+idcpanelpack.score+" 分)");
                    $("#achieveTime").html(idcpanelpack.achievetime);
                    changeRankingIcon(idcpanelpack.ranking,idcpanelpack.oldranking);
                    var rightcount = parseInt(idcpanelpack.rightcount);
                    var wrongcount = parseInt(idcpanelpack.wrongcount);
                    var correctrate = Math.round(rightcount/(rightcount+wrongcount)*100)+"%";
                    if(rightcount+wrongcount <= 0){
                        correctrate = "100%";
                    }
                    $("#ban-info").css("display","none");
                    $("#active-info").css("display","none");
                    
                    if(idcpanelpack.isbanned >0){
                        $("#ban-info").css("display","block");
                    }
                    
                    if(idcpanelpack.active >2){
                        $("#active-info").css("display","block");
                    }
                    
                    
                    $("#captchaPanel").css("display","block");
                    
                    $('#rank').animateNumber(
                      {
                        number: idcpanelpack.ranking,
                        easing: 'easeInQuad'
                      }
                    );

                    $('#score').animateNumber(
                      {
                        number: idcpanelpack.score,
                        easing: 'easeInQuad'
                      },1000
                    );
                    
                    var percent_number_step = $.animateNumber.numberStepFactories.append('%');
                    $('#correctRate').prop('number',$('#correctRate').html().replace("%","")).animateNumber(
                        {
                            number: correctrate,
                            easing: 'easeInQuad',
                            numberStep:percent_number_step
                        },1800
                    );
                    
                    
                });
                
            }
            
            function changeRankingIcon(ranking,oldranking){
                ranking = parseInt(ranking);
                oldranking = parseInt(oldranking);
                if(ranking>oldranking){
                    $("#ranking-icon").attr("data-type","down");
                }else if(ranking<oldranking){
                    $("#ranking-icon").attr("data-type","up");
                }else if(ranking==oldranking){
                    $("#ranking-icon").attr("data-type","keep");
                }
            }
            
            
            function showanswerpanel(){
                $("#captchaPanel").css("display","none");
                $("#answerpanel").css("display","none");
                $("#infopanel").css("display","none");
                
                
                
                var answerpanelpack = {};
                getDataFromServer("getQuestionPack",function(answerpanelpack){
                    if(answerpanelpack.err != 0000){
                        $("#checkidctips").html(answerpanelpack.errinfo);
                        $("#answerpanel").css("display","block");
                        return;
                    }

                    //绑定数据
                    $("#addscore").html(answerpanelpack.addscore);
                    $("#minusscore").html("-"+answerpanelpack.minusscore);
                    $("#question").html(answerpanelpack.question);
                    questionid = answerpanelpack.questionid;
                    availabletime = answerpanelpack.availabletime * 1000;
                    $("#answer-a").html("A."+answerpanelpack.answera);
                    $("#answer-b").html("B."+answerpanelpack.answerb);
                    $("#answer-c").html("C."+answerpanelpack.answerc);
                    $("#answer-d").html("D."+answerpanelpack.answerd);
                    
                    $("#answerbuttongroup").css("display","inline");
                    $("#processinganimation").css("display","none");
                    
                    $("#answerpanel").css("display","block");
                    startAnswerTime = new Date().getTime();
                    timer1 = setInterval('timejudge()',10);
                });
                
                
                
            }
            
            
            function showinfopanel(appendix){
                $("#captchaPanel").css("display","none");
                $("#answerpanel").css("display","none");
                $("#infopanel").css("display","none");
                
                var icontype = appendix.split("::")[0];
                var info = appendix.split("::")[1];
                
                $('#infopanel-icon').removeClass();
                if(icontype == "err"){
                    $('#infopanel-icon').addClass("glyphicon glyphicon-remove");
                }else if(icontype == "success"){
                    $('#infopanel-icon').addClass("glyphicon glyphicon-ok");
                }
                
                $('#infopanel-info').html(info);
                
                
                $("#infopanel").css("display","block");
            }
            
            
            //设置cookie
            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays*24*60*60*1000));
                var expires = "expires="+d.toUTCString();
                document.cookie = cname + "=" + cvalue + "; " + expires;
            }
            //获取cookie
            function getCookie(cname) {
                var name = cname + "=";
                var ca = document.cookie.split(';');
                for(var i=0; i<ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0)==' ') c = c.substring(1);
                    if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
                }
                return "";
            }
            
            
            
            
            
            $(document).ready(function(){
                //---------------idcpanel的js内容
                
                
                //判断是不是老玩家
                $.ajax({
                    url: "<?php echo ROOT_PREFIX.API;?>/mobile",
                    type: "POST",
                    data:{
                        'what':'isOldHand'
                    },
                    error:function(){
                        
                    },
                    success: function(data,status){
                        result = JSON.parse(data);
                        if(result.code == 0000){
                            if(result.isOldHand == true && getCookie("oldComercopythat")!="true"){
                                $("#oldcomertip").css("display","block");
                            }
                        }
                    }
                });
                
                //我知道了的触发
                $("#OldComercopythat").click(function(){
                    $("#oldcomertip").css("display","none");
                    setCookie("oldComercopythat","true",10);
                });
                
                $("#refresh").click(function(){
                    $("#refresh").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;刷新中");
                    $("#refresh").attr("disabled",true);
                    var idcpanelpack = {};
                    getDataFromServer("getProfile",function(idcpanelpack){
                        if(idcpanelpack.err != 0000){
                            $("#checkidctips").html(idcpanelpack.errinfo);
                            $("#refresh").attr("disabled",false);
                            return;
                        }

                        //绑定数据
//                        $("#rank").html(idcpanelpack.ranking);
                        $("#answerpanelranking").html(idcpanelpack.ranking);
                        $("#playerName").html(idcpanelpack.name);
                        $("#answerpanelname").html(idcpanelpack.name);
//                        $("#score").html(idcpanelpack.score+"分");
                        $("#answerpanelscore").html("  (你的总分: "+idcpanelpack.score+" 分)");
                        $("#achieveTime").html(idcpanelpack.achievetime);
                        changeRankingIcon(idcpanelpack.ranking,idcpanelpack.oldranking);
                        var rightcount = parseInt(idcpanelpack.rightcount);
                        var wrongcount = parseInt(idcpanelpack.wrongcount);
                        var correctrate = Math.round(rightcount/(rightcount+wrongcount)*100)+"%";
                        if(rightcount+wrongcount <= 0){
                            correctrate = "100%";
                        }
                        
                        $("#ban-info").css("display","none");
                        $("#active-info").css("display","none");

                        if(idcpanelpack.isbanned >0){
                            $("#ban-info").css("display","block");
                        }

                        if(idcpanelpack.active >=2){
                            $("#active-info").css("display","block");
                        }

                        $("#captchaPanel").css("display","block");
                        
                        
                        
                        
                        $('#rank').prop('number',$('#rank').html()).animateNumber(
                          {
                            number: idcpanelpack.ranking,
                            easing: 'easeInQuad'
                          }
                        );

                        $('#score').prop('number',$('#score').html()).animateNumber(
                          {
                            number: idcpanelpack.score,
                            easing: 'easeInQuad'
                          },1000
                        );

                        var percent_number_step = $.animateNumber.numberStepFactories.append('%');
                        $('#correctRate').prop('number',$('#correctRate').html().replace("%","")).animateNumber(
                          {
                            number: correctrate,
                            easing: 'easeInQuad',
                            numberStep:percent_number_step
                          },1800
                        );
                        
                        
                    });
                    
                    var i=5; //刷新限定秒数
                    var refreshtimer = setInterval(refreshtimecount,1000);
                    
                    function refreshtimecount(){
                        $("#refresh").html("刷新("+i+"s)");
                        i--;
                        if(i==0){
                            $("#refresh").html("<i class=\"fa fa-refresh\" aria-hidden=\"true\"></i>&nbsp;刷新");
                            $("#refresh").attr("disabled",false);
                            clearInterval(refreshtimer);
                        }
                    }
                });

                $("#goAnswer").click(function(e){
                    e.preventDefault();
                    if($("#goAnswer").html()=="进入疯狂抢答"){
                        $("#goAnswer").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;正在进入中,稍安勿躁");
                        $.ajax({
                            url: "<?php echo ROOT_PREFIX.API;?>/mobile",
                            type: "POST",
                            data:{
                                'what':'checkCaptcha',
                                'captcha':$('#captcha').val()
                            },
                            success: function(data,status){
                                data = JSON.parse(data);
                                if(data.code == 0000){
                                    $("#checkidctips").html(data.info);
                                    $("#goAnswer").html("进入疯狂抢答");
                                }else{
                                    $("#checkidctips").html(data.info);
                                    $("#goAnswer").html("进入疯狂抢答");
                                }
                            }
                        });

                }
                });
            });
            
            
            
            //-------------答题面板的js内容
            
            $(document).ready(function(){




                $("#alertclose").click(function(){
                    $("#errorbox").css("display","none");
                });
            });

            function timejudge(){
                var now = new Date().getTime();
                if(now-startAnswerTime>=availabletime){
                    $("#answerbuttongroup").css("display","none");
                    $("#processinganimation").css("display","inline");
                    clearInterval(timer1);
                    scanstate('infopage','err::你没有在规定的时间内作答~');

                    $.ajax({
                        url: "<?php echo ROOT_PREFIX.API;?>/mobile",
                        type: "POST",
                        data:{
                            'what':'iveBeenTimeout',
                            'questionId':questionid
                        },
                        success: function(data,status){

                        }
                    });
                }
                var widthpercent = (availabletime-(now-startAnswerTime))/availabletime * 100;
                $("#timebarvalue").css('width',widthpercent+"%");
            }
            
            
            
            function submitSolution(choose){
                clearInterval(timer1);
                $("#answerbuttongroup").css("display","none");
                $("#processinganimation").css("display","inline");
                $.ajax({
                    url: "<?php echo ROOT_PREFIX.API;?>/mobile",
                    type: "POST",
                    data:{
                        'what':'submitSolution',
                        'choose':choose,
                        'questionId':questionid
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
    </body>
</html>