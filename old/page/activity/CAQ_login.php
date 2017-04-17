<?php 
require_once("../config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');
?>

<?php
if(is_array($_GET)&&count($_GET)>0){ 
    if(isset($_GET["tip"])){ 
        $tips=$_GET["tip"];//存在 
    }else{
        $tips="";
    }
}else{
    $tips="";
}

?>

<html>
    <head>
        <title>全民答疯抢</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <link href="//cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/caq_login.css">
        <script src="//cdn.bootcss.com/jquery/2.2.1/jquery.min.js" defer></script>
        <link href="//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <script src="//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js" defer></script>
    </head>

    <body  style="background-color:#eee">
        <center>
            <img src="/page/img/banner_quanmindafengqiang.png" class="img-responsive" alt="Responsive image">
        </center>
        <div class='container'>
            <div class="col-md-12 col-sm-12 col-xs-12 div_mainblock">
                <br>
                <br>
                <div class="div-browser-dectect-info" id="browser-detect-info">
                    <span class="glyphicon glyphicon-exclamation-sign">&nbsp;请勿在微信浏览器中直接操作本网页<br><br>点击右上角的按钮->&nbsp;<strong>"在浏览器中打开"<strong></span>
                </div>
                <noscript>
                    <div>我们检测到你的浏览器没有开启&nbsp;<strong>Javascript</strong>&nbsp;的功能,请尝试在浏览器设置中开启，以免影响参赛效果，如需帮助可以向现场工作人员求助。</div>
                </noscript>
                <center>
                    <br>
                    <div class="div-input-border col-md-12 col-sm-12 col-xs-12 ">
                        <input id="usrnameInput" placeholder="请输入你的姓名">
                        <span class="glyphicon glyphicon-user" style="color:#787878"></span>
                        <br style="clear:both">
                    </div>
                    <br>
                    <br>
                    <div class="div-input-border col-md-12 col-sm-12 col-xs-12 ">
                        <input id="telInput" placeholder="请输入你的联系方式">
                        <span class="glyphicon glyphicon-phone" style="color:#787878"></span>
                        <br style="clear:both">
                    </div>
                    <br>
                    <br>

                    <?php
                    $nc = new sqlhelper();
                    $opecode = $nc->connect();
                    if($opecode!=0){

                    }
                    $result = $nc->mysql("select * from question_config where keyname='isreging' limit 1");
                    $row = mysql_fetch_array($result);
                    if($row['value'] == 'true'){
                    ?>
                    <button class="div-caq-button col-md-12 col-sm-12 col-xs-12 " onclick="register()" id="registerbtn">登记并进入疯狂抢答</button>
                    <?php
                    }else{
                    ?>
                    <button class="div-caq-button col-md-12 col-sm-12 col-xs-12 " onclick="login()" id="loginbtn">进入疯狂抢答</button>
                    <?php
                    }
                    ?>
                    
                    <p id="logintips" class="p-error"><?php echo $tips;?></p>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <p class="p-bottom-extras">科创社·科技部 技术提供</p>
                </center>
            </div>
        </div>



        <script>
            //判断微信浏览器
            function isWeiXin(){
                var ua = window.navigator.userAgent.toLowerCase();
                if(ua.match(/MicroMessenger/i) == 'micromessenger'){
                    return true;
                }else{
                    return false;
                }
            }

            if(isWeiXin()){
                document.getElementById("browser-detect-info").style.cssText = "display:block";
            }

            function login(){
                if($("#loginbtn").html()=="进入疯狂抢答" && !isWeiXin()){
                    $("#loginbtn").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;努力为你加载中~爱你哟~");
                    $.ajax({
                        url: "<?php echo PAGE_PATH;?>php/CAQ_function.php",
                        type: "POST",
                        data:{
                            'what':'login',
                            'name':$('#usrnameInput').val(),
                            'tel':$('#telInput').val()
                        },
                        success: function(data,status){
                            data = JSON.parse(data);
                            if(data.code == 0000){
                                window.location.href = "CAQ_mobile.php";
                            }else{
                                $("#logintips").html(data.info);
                                $("#loginbtn").html("进入疯狂抢答");
                            }
                        }
                    });
                }
            }

            function register(){
                if($("#registerbtn").html()=="登记并进入疯狂抢答" && !isWeiXin()){
                    $("#registerbtn").html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;努力为你加载中~爱你哟~");
                    $.ajax({
                        url: "<?php echo PAGE_PATH;?>php/CAQ_function.php",
                        type: "POST",
                        data:{
                            'what':'register',
                            'name':$('#usrnameInput').val(),
                            'tel':$('#telInput').val()
                        },
                        success: function(data,status){
                            data = JSON.parse(data);
                            if(data.code == 0000){
                                window.location.href = "CAQ_mobile.php";
                            }else{
                                $("#logintips").html(data.info);
                                $("#loginbtn").html("登记并进入疯狂抢答");
                            }
                        }
                    });
                }
            }
        </script>

    </body>

</html>