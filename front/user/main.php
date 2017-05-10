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

        <!-- build:js /assets/js/main.js -->
        <script src="/front/assets/js/jquery-1.12.4.js"></script>
        <script src="/front/assets/js/bootstrap.js"></script>
        <script src="/front/assets/js/jquery.easing.compatibility.1.4.1.js"></script>
        <script src="/front/assets/js/jquery.animateNumber.min.js"></script>
        <!-- endbuild -->

        <!-- build:css /assets/css/main.css -->
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
                <div class="color-block score-container" id="scoreBtn">
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
                <div class="score-why">
                    <div class="caret"></div>
                    <div class="content">
                        <span id="reasonForScore">因为答错被扣分了</span>
                        <a target="_blank" href="score-rules"><span class="fa fa-question-circle-o"></span>计分细则</a>
                    </div>
                </div>
            </div>
<!--            个人信息完-->

            <br>
            <div class="div-alert-info" id="ban-info"><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;&nbsp;很抱歉,您已经连续输入五次错误的验证码,您的账号已经被封禁,请向现场工作人员求助,带来不便敬请谅解</div>
            
            <div class="div-alert-info" id="active-info"><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;&nbsp;哈喽？您还在进行活动吗？您的挂机行为已经被系统扣除了一定分数，请一定积极参与到游戏中来哦。</div>

            <div class="div-alert-info" id="active-warn"><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;&nbsp;逃避答题 = 消极比赛，为了维护游戏环境，继续挂机每道题结算时将被扣除答错分数的1.5倍哦。</div>

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

        </section>
        
        <!--答题面板-->
<!--        <section id="answerpanel" style="display:none">-->
        <section id="answerpanel">

            <div id="question-area">
                <div class="question-sort" id="answerpanelranking">N</div>
                <div class="question-bg">
                    <div class="scores-container">
                        <div class="minusscore" id="minusscore">0</div>
                        <div class="addscore" id="addscore">0</div>
                    </div>
                    <div class="question" id="question">loading...</div>
                    <div class="time-container">
                        <h5 class="title"><i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;TIME</h5>
                        <h5 class="second" id="second">10s</h5>
                        <div class="timebar" id="timebar">
                            <div class="timebarvalue" id="timebarvalue"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="answer-group">
                <div class="answer" onclick="submitSolution('A')">
                    <div class="index">A</div>
                    <div class="content" id="answer-a"></div>
                </div>
                <div class="answer" onclick="submitSolution('B')">
                    <div class="index">B</div>
                    <div class="content" id="answer-b"></div>
                </div>
                <div class="answer" onclick="submitSolution('C')">
                    <div class="index">C</div>
                    <div class="content" id="answer-c"></div>
                </div>
                <div class="answer" onclick="submitSolution('D')">
                    <div class="index">D</div>
                    <div class="content" id="answer-d"></div>
                </div>
            </div>

            <div class="errorbox" id="errorbox" style="display:none;">
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" id="alertclose"><span aria-hidden="true">&times;</span></button>
                    <span id="errortips"></span>
                </div>
            </div>

            <div id="processinganimation" style="display:none;">
                <h1><i class="fa fa-spinner fa-spin"></i></h1>
                <br>
                <h1 class="allinfo">努力提交答案ing~</h1>
            </div>

        </section>
        
        
        <!--提示信息页面-->
        <section id="infopanel" style="display:none">
            <div class="info-container">
                <div class="info-bg">
                    <h1 class="icon"><span class="fa fa-check" id="infopanel-icon"></span></h1>
                    <h1 id="infopanel-info">loading</h1>
                </div>


                <button class="submit_btn" onclick="scanstate('idc')"><span class="fa fa-arrow-circle-left"></span>&nbsp;&nbsp;回到输入验证码的界面</button>


            </div>
        </section>



        <p class="p-bottom-extras">科创社·科技部 技术提供</p>
        
        
        
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
                    $("#achieveTime").html(idcpanelpack.achievetime);
                    changeRankingIcon(idcpanelpack.ranking,idcpanelpack.oldranking);
                    var rightcount = parseInt(idcpanelpack.rightcount);
                    var wrongcount = parseInt(idcpanelpack.wrongcount);
                    var correctrate = Math.round(rightcount/(rightcount+wrongcount)*100)+"%";
                    if(rightcount+wrongcount <= 0){
                        correctrate = "100%";
                    }


                    //设置加扣分原因
                    setReasonForScore(idcpanelpack['reason-for-score']);


                    $("#ban-info").css("display","none");
                    $("#active-info").css("display","none");
                    $("#active-warn").css("display","none");

                    if(idcpanelpack.isbanned >0){
                        $("#ban-info").css("display","block");
                    }

                    if(idcpanelpack.active >2){
                        $("#active-info").css("display","block");
                    }
                    if(idcpanelpack.active ==2){
                        $("#active-warn").css("display","block");
                        setCookie("passiveWarn","true",10);
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
                    $("#addscore").html("+"+answerpanelpack.addscore);
                    $("#minusscore").html("-"+answerpanelpack.minusscore);
                    $("#question").html(answerpanelpack.question);
                    questionid = answerpanelpack.questionid;
                    availabletime = answerpanelpack.availabletime * 1000;
                    $("#answer-a").html(answerpanelpack.answera);
                    $("#answer-b").html(answerpanelpack.answerb);
                    $("#answer-c").html(answerpanelpack.answerc);
                    $("#answer-d").html(answerpanelpack.answerd);
                    
                    $("#answer-group").css("display","block");
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
            
            //设置分数变化原因
            function setReasonForScore(reason){
                var reasonForScore = reason;
                switch (reasonForScore){
                    case "none":
                        reasonForScore = "分数没有丝毫波动";
                        break;
                    case "last":
                        reasonForScore = "抢答速度落后于全场75%的人，并且没答对，扣分";
                        break;
                    case "wrong":
                        reasonForScore = "答错了被扣分嘤嘤嘤";
                        break;
                    case "timeout":
                        reasonForScore = "答题超时被扣分了QAQ";
                        break;
                    case "joined":
                    case "wait":
                        reasonForScore = "￣へ￣ 没有提交答案被扣分了";
                        break;
                    case "correct":
                        reasonForScore = "(≧∇≦)ﾉ 答对加分";
                        break;
                    case "correctUnlucky":
                        reasonForScore = "答对了但是手速慢了，分数没变化QAQ";
                        break;
                    case "passive":
                        reasonForScore = "唔，由于挂机被扣分了呢";
                        break;
                    default:
                        reasonForScore = "我们也不知道你的分数发生了什么变化";
                        break;
                }
                $("#reasonForScore").html(reasonForScore);
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

                $("#scoreBtn").click(function(){
                    $(".score-why").toggleClass("show");
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

                        //设置加扣分原因
                        setReasonForScore(idcpanelpack['reason-for-score']);

                        
                        $("#ban-info").css("display","none");
                        $("#active-info").css("display","none");
                        $("#active-warn").css("display","none");

                        if(idcpanelpack.isbanned >0){
                            $("#ban-info").css("display","block");
                        }

                        if(idcpanelpack.active >2){
                            $("#active-info").css("display","block");
                        }
                        if(idcpanelpack.active ==2 && getCookie("passiveWarn") != "true"){
                            $("#active-warn").css("display","block");
                            setCookie("passiveWarn","true",10);
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
                    $("#answer-group").css("display","none");
                    $("#processinganimation").css("display","block");
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
                $("#second").html(Math.round((availabletime-(now-startAnswerTime))/1000)+"s");
            }
            
            
            
            function submitSolution(choose){
                clearInterval(timer1);
                $("#answer-group").css("display","none");
                $("#processinganimation").css("display","block");
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
                            $("#answer-group").css("display","block");
                            $("#processinganimation").css("display","none");
                            timer1 = setInterval('timejudge()',10);
                        }
                    }
                });
            }
        </script>
    </body>
</html>