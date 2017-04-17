<?php 
require_once("../config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');


//验证在线
require_once("../php/CAQ_function.php");
$err = checkCAQlogin();
if($err->{'code'}!=0000){
    $tip = $err->{'info'};
    die("<script>window.location.href=\"CAQ_login.php?tip=$tip\"</script>");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>全民答疯抢</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/caq_mobile.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <script src="//cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
        <link href="//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <script src="//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js" defer></script>
        <script src="//cdn.bootcss.com/jquery-easing/1.3/jquery.easing.min.js"></script>
        <script src="../../plugin/js/jquery.animateNumber.min.js"></script>

    </head>

    <body id="maincontainer" style="background-color:#fafafa">
        
        <!--等待输入验证码页面-->
        <section id="idcpanel">
            <center>
                <img src="/page/img/banner_quanmindafengqiang_ad.png" class="img-responsive" alt="Responsive image">
            </center>
            <div class="div-profile-container">
                <div class="div-profile-rankcircle" id="rank">
                0
                </div>
                <div class="div-profile-nameline">
                    <div class="div-profile-name" id="profilename">loading</div>
                    <img src="../img/caq_rankinglist_up.png" class="img-rankinglist-changeicon" id="rankingiconUp">
                    <img src="../img/caq_rankinglist_down.png" class="img-rankinglist-changeicon" id="rankingiconDown">
                    <img src="../img/caq_rankinglist_keep.png" class="img-rankinglist-changeicon" id="rankingiconKeep">

                    <div class="div-profile-score" id="score">0分</div>
                    <img src="../img/caq_mobile_coin.png" class="img-profile-coin" id="rankingicon">
                </div>
                <div class="div-profile-secondline">
                    <i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;分数达成时间:
                    <span id="achievetime">loading..</span>
                </div>
                <div class="div-profile-thirdline">
                    <i class="fa fa-check" aria-hidden="true"></i>&nbsp;准确率:  
                    <span id="correctrate">100%</span>
                </div>
                <button class="btn-refresh" id="refresh"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;刷新</button>
            </div>
            <br>
            <div class="div-alert-info" id="ban-info"><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;&nbsp;很抱歉,您已经连续输入三次错误的验证码,您的账号已经被封禁,请向现场工作人员求助,带来不便敬请谅解</div>
            
            <div class="div-alert-info" id="active-info"><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;&nbsp;很抱歉,您的行为被系统断定为消极比赛,如果您没有积极参与比赛,将被扣除一定分数</div>
            
            <div class="container" id="oldcomertip" style="display:none;">
                <div class="alert alert-success" role="alert">
                    <strong>·&nbsp;勇士归来&nbsp;·</strong><br><br>
                    欢迎回来!去年参与过比赛的勇士啊~<br><br>为了呈现一场高质量的大学活动~我们在各个方面都做了很多巨大改进<br><br>谢谢你能来见证我们的成长!<br>愿你在今年的比赛里取得更加优异的成绩!
                    <br>
                    <br>
                    <button type="button" class="btn btn-success" id="OldComercopythat">我知道啦~</button>
                </div>
            </div>
            <br>
            <br>
            <br>


            <div class="div-profile-form">
                <div class="div-input-border" style="font-size:20px;">
                    <input id="idcInput" placeholder="这里输入验证码" style="text-align:center;">
                    <span style="color:#787878"></span>
                    <br style="clear:both">
                </div>
                <br>
                <button class="div-caq-button" id="goAnswer">进入疯狂抢答</button>
            </div>
            <script>
            
            </script>
            <p id="checkidctips" class="p-error"></p>
            <br style="clear:both">
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
                        <div class="div-answer-answer-block" onclick="submitanswer('A')">
                            <span id="answer-a"></span>
                            <br style="clear:both">
                        </div>
                    </div>
                    <br style="clear:both">
                    <br style="clear:both">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="div-answer-answer-block" onclick="submitanswer('B')">
                            <span id="answer-b"></span>
                            <br style="clear:both">
                        </div>
                    </div>
                    <br style="clear:both">
                    <br style="clear:both">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="div-answer-answer-block" onclick="submitanswer('C')">
                            <span id="answer-c"></span>
                            <br style="clear:both">
                        </div>
                    </div>
                    <br style="clear:both">
                    <br style="clear:both">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="div-answer-answer-block" onclick="submitanswer('D')">
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
            var availabletime = 10;
            var now = 0;
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
                    url: "CAQ_mobile_parts.php",
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
                $("#idcpanel").css("display","none");
                $("#answerpanel").css("display","none");
                $("#infopanel").css("display","none");
                
                
                var idcpanelpack = {};
                getDataFromServer("getIdcPanelPack",function(idcpanelpack){
                    if(idcpanelpack.err != 0000){
                        $("#checkidctips").html(idcpanelpack.errinfo);
                        $("#idcpanel").css("display","block");
                        return;
                    }

                    //绑定数据
                    $('#idcInput').val("");
//                    $("#rank").html(idcpanelpack.ranking);
                    $("#answerpanelranking").html(idcpanelpack.ranking);
                    $("#profilename").html(idcpanelpack.name);
                    $("#answerpanelname").html(idcpanelpack.name);
//                    $("#score").html(idcpanelpack.score+"分");
                    $("#answerpanelscore").html("  (你的总分: "+idcpanelpack.score+" 分)");
                    $("#achievetime").html(idcpanelpack.achievetime);
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
                    
                    
                    $("#idcpanel").css("display","block");
                    
                    $('#rank').animateNumber(
                      {
                        number: idcpanelpack.ranking,
                        easing: 'easeInQuad'
                      }
                    );
                    
                    var score_number_step = $.animateNumber.numberStepFactories.append(' 分');
                    $('#score').animateNumber(
                      {
                        number: idcpanelpack.score,
                        easing: 'easeInQuad',
                        numberStep: score_number_step
                      },1000
                    );
                    
                    var percent_number_step = $.animateNumber.numberStepFactories.append('%');
                    $('#correctrate').prop('number',100).animateNumber(
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
                    $("#rankingiconUp").css("display","none");
                    $("#rankingiconDown").css("display","block");
                    $("#rankingiconKeep").css("display","none");
                }else if(ranking<oldranking){
                    $("#rankingiconUp").css("display","block");
                    $("#rankingiconDown").css("display","none");
                    $("#rankingiconKeep").css("display","none");
                }else if(ranking==oldranking){
                    $("#rankingiconUp").css("display","none");
                    $("#rankingiconDown").css("display","none");
                    $("#rankingiconKeep").css("display","block");
                }
            }
            
            
            function showanswerpanel(){
                $("#idcpanel").css("display","none");
                $("#answerpanel").css("display","none");
                $("#infopanel").css("display","none");
                
                
                
                var answerpanelpack = {};
                getDataFromServer("getAnswerPanelPack",function(answerpanelpack){
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
                    now = availabletime;
                    timer1 = setInterval('timejudge()',10);
                });
                
                
                
            }
            
            
            function showinfopanel(appendix){
                $("#idcpanel").css("display","none");
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
                    url: "../php/CAQ_function.php",
                    type: "POST",
                    data:{
                        'what':'whetheroldcomer'
                    },
                    error:function(){
                        
                    },
                    success: function(data,status){
                        result = JSON.parse(data);
                        if(result.code == 0000){
                            if(result.isoldcomer == "true" && getCookie("oldComercopythat")!="true"){
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
                    getDataFromServer("getIdcPanelPack",function(idcpanelpack){
                        if(idcpanelpack.err != 0000){
                            $("#checkidctips").html(idcpanelpack.errinfo);
                            $("#refresh").attr("disabled",false);
                            return;
                        }

                        //绑定数据
//                        $("#rank").html(idcpanelpack.ranking);
                        $("#answerpanelranking").html(idcpanelpack.ranking);
                        $("#profilename").html(idcpanelpack.name);
                        $("#answerpanelname").html(idcpanelpack.name);
//                        $("#score").html(idcpanelpack.score+"分");
                        $("#answerpanelscore").html("  (你的总分: "+idcpanelpack.score+" 分)");
                        $("#achievetime").html(idcpanelpack.achievetime);
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

                        $("#idcpanel").css("display","block");
                        
                        
                        
                        
                        $('#rank').prop('number',$('#rank').html()).animateNumber(
                          {
                            number: idcpanelpack.ranking,
                            easing: 'easeInQuad'
                          }
                        );

                        var score_number_step = $.animateNumber.numberStepFactories.append(' 分');
                        $('#score').prop('number',$('#score').html().replace("分","")).animateNumber(
                          {
                            number: idcpanelpack.score,
                            easing: 'easeInQuad',
                            numberStep: score_number_step
                          },1000
                        );

                        var percent_number_step = $.animateNumber.numberStepFactories.append('%');
                        $('#correctrate').prop('number',$('#correctrate').html().replace("%","")).animateNumber(
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
                            $("#refresh").html("刷新");
                            $("#refresh").attr("disabled",false);
                            clearInterval(refreshtimer);
                        }
                    }
                });

                $("#goAnswer").click(function(){
                    if($("#goAnswer").html()=="进入疯狂抢答"){
                        $("#goAnswer").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;正在进入中,稍安勿躁");
                        $.ajax({
                            url: "../php/CAQ_function.php",
                            type: "POST",
                            data:{
                                'what':'checkidc',
                                'idc':$('#idcInput').val()
                            },
                            success: function(data,status){
                                data = JSON.parse(data);
                                if(data.code == 0000){
                                    $("#checkidctips").html(data.info);
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
                if(now<0){
                    $("#answerbuttongroup").css("display","none");
                    $("#processinganimation").css("display","inline");
                    clearInterval(timer1);
                    scanstate('infopage','err::你没有在规定的时间内作答~');

                    $.ajax({
                        url: "../php/CAQ_function.php",
                        type: "POST",
                        data:{
                            'what':'submitanswertimeout',
                            'questionid':questionid
                        },
                        success: function(data,status){

                        }
                    });
                }
                now = now - 10;
                var widthpercent = now/availabletime * 100;
                $("#timebarvalue").css('width',widthpercent+"%");
            }
            
            
            
            function submitanswer(choose){
                clearInterval(timer1);
                $("#answerbuttongroup").css("display","none");
                $("#processinganimation").css("display","inline");
                $.ajax({
                    url: "../php/CAQ_function.php",
                    type: "POST",
                    data:{
                        'what':'submitanswer',
                        'choose':choose,
                        'questionid':questionid
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