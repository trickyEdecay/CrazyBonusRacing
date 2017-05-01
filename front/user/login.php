<?php
//验证在线,如果在线直接跳转到登录后的页面
require_once("login.php");
$err = getLoginState();
$err= json_decode($err,true);
if($err['code']==0000){
    die("<script>location.href='main'</script>");
}

if(isset($_GET["tip"])){
    $tips=$_GET["tip"];//存在
}elseif(isset($tip)){
    $tips = $tip;
}else{
    $tips = "";
}

?>

<html>
    <head>
        <title>全民答疯抢</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">

        <!-- build:js /assets/js/user/login.js -->
        <script src="/front/assets/js/jquery-1.12.4.js"></script>
        <!-- endbuild -->

        <!-- build:css /assets/css/user/login.css -->
        <link href="/front/assets/style/font-awesome.css" rel="stylesheet">
        <link href="/front/assets/style/user/login.css" rel="stylesheet">
        <!-- endbuild -->
    </head>

    <body>
        <div class="top_warn" id="wechatDetected">
            <div class="warp">
                <div class="icon">
                    <span class="fa fa-exclamation-circle"></span>
                </div>
                <div class="content">
                    请勿在微信浏览器中直接操作本网页<br>点击右上角的按钮->&nbsp;<strong>"在浏览器中打开"</strong>
                </div>
            </div>
        </div>
        <div class="top_warn" id="cookieDisabledDetected">
            <div class="warp">
                <div class="icon">
                    <span class="fa fa-times-circle">
                </div>
                <div class="content">
                    你的浏览器没有开启 <strong>cookie</strong> 功能哦，请尝试在浏览器设置中打开并刷新页面，以免影响参赛效果，如需帮助可以向现场工作人员求助。
                </div>
            </div>
        </div>
        <noscript>
            <div class="warp">
                <div class="icon">
                    <span class="fa fa-times-circle">
                </div>
                <div class="content">
                    我们检测到你的浏览器没有开启&nbsp;<strong>Javascript</strong>&nbsp;的功能,请尝试在浏览器设置中打开并刷新页面，以免影响参赛效果，如需帮助可以向现场工作人员求助。
                </div>
            </div>
        </noscript>
        <header>
            <img id="logo" src="/front/assets/img/logo_withshadow.png">
        </header>
        <form id="login_form">
            <div class="inner">
            <div class="input_group">
                <label for="player_name"><span data-for="icon" class="login_icon_player"></label>
                <div class="input_container">
                    <input id="player_name" placeholder="请输入你的姓名" type="text" tabindex="1">
                </div>
                <div class="bottom_line">
                    <div class="red_bottom_line"></div>
                </div>
            </div>
            <div class="input_group">
                <label for="player_tel"><span data-for="icon" class="login_icon_tel"></span></label>
                <div class="input_container">
                    <input id="player_tel" placeholder="请输入你的联系方式" type="tel" tabindex="2">
                </div>
                <div class="bottom_line">
                    <div class="red_bottom_line"></div>
                </div>
            </div>
            </div>

            <?php
            $db_questionConfig = new questionConfig();
            if(!$db_questionConfig->connect()){
                $tips = "看似您的网络有问题哦";
            }
            $isReging = $db_questionConfig->getIsReging();
            if($isReging == 'true'){
                ?>
                <button class="submit_btn" tabindex="3" data-submit-type="register">登记并进入疯狂抢答</button>
                <?php
            }else{
                ?>
                <button class="submit_btn" tabindex="3" data-submit-type="login">进入疯狂抢答</button>
                <?php
            }
            $db_questionConfig->close();
            ?>

            <p id="loginTips"><?php echo $tips;?></p>
        </form>
        <script>
            $(function(){

                //添加动画元素
                var $loginInput = $(".input_container>input");
                $loginInput.focus(function(){
                   $(this).parent().parent().find(".red_bottom_line").addClass("active");
                   $(this).parent().parent().find("[data-for='icon']").addClass("active");
               });
                $loginInput.blur(function(){
                   $(this).parent().parent().find(".red_bottom_line").removeClass("active");
                   $(this).parent().parent().find("[data-for='icon']").removeClass("active");
               });

                $loginInput.keydown(function(){
                    $("#loginTips").html("");
                });

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
                    $("#wechatDetected").css("display","block");
                }

                if(!navigator.cookieEnabled){
                    $("#cookieDisabledDetected").css("display","block");
                }


                //登录 or 注册
                var $loginForm = $("#login_form");

                $loginForm.find("button").click(function(){
                    $loginForm.submit();
                });

                $loginForm.submit(function(e){
                    e.preventDefault();
                    var $submitButton = $loginForm.find("button");
                    var btnText = $submitButton.html();
                    var submitType = $submitButton.attr("data-submit-type");

                    if(isWeiXin()){
                        $("#loginTips").html("本页面仅允许在第三方浏览器中打开哦");
                        return;
                    }

                    if(btnText.indexOf("进入疯狂抢答") >= 0){
                        $submitButton.html("<i class=\"fa fa-spinner fa-spin\"></i>&nbsp;&nbsp;努力为你加载中~爱你哟~");
                        $.ajax({
                            url: "<?php echo ROOT_PREFIX.API;?>/login",
                            type: "POST",
                            data:{
                                'what':submitType,
                                'name':$('#player_name').val(),
                                'tel':$('#player_tel').val()
                            },
                            success: function(data,status){
                                data = JSON.parse(data);
                                if(data.code == 0000){
                                    window.location.href = "<?php echo ROOT_PREFIX;?>/main";
                                }else{
                                    $("#loginTips").html(data.info);
                                    $submitButton.html(btnText);
                                }
                            }
                        });
                    }
                });

            });
        </script>

    </body>

</html>