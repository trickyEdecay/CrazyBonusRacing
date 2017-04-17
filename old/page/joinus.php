<?php 
require_once("config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');
?>

<!DOCTYPE html>
<?php
if(isset($_COOKIE['ukey'])){ //先判断cookie存在与否
    list($name, $submittime,$handinmoneykey) = explode(':::', $_COOKIE['ukey']);
    ?>
    <script type="text/javascript">
        window.location.href = "registercode<?php echo $handinmoneykey; ?>";
    </script>
    <?php
}
?>
<html>

<head>
    <title>加入科创社</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <?php echo constant( 'JQuery_DEFINE');echo constant( 'BOOTSTRAP_DEFINE');?>
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>joinus.less">
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>common.less">
    <?php echo LESS_DEFINE; ?>
</head>

<body>
    <div class='container mainContainer'>
        <div class="col-md-12 col-sm-12 col-xs-12 div_mainblock">
            <h1 class="font-coffee">科创社网上报名</h1>
            <p class="font-lightcoffee">Join Science Innovation Agency On Line</p>
            <p class="font-yellow">
                <div class="div_divideline"></div>&nbsp;&nbsp;<img src="/page/img/littleicon40x40.png" alt="科创社logo" class="img-circle">&nbsp;&nbsp;<div class="div_divideline"></div>
            </p>
        
            <br><br><br>
            <p>请认真填写表格,每个问题对应的都有填写提示噢~</p>
            <p style="display:none">足不出户报名的网页已经挂出~报名表只能于18号提交噢~</p>
            <br><br><br>
            <a class="a_button_gray" href="registercode">我已经有报名码 -></a>
            <br><br><br>
            
            <!--控制提示的收起查看-->
            <script type="text/javascript"> 
                $(document).ready(function(){
                    $(".tipsOfferFlip").click(function(){
                        $(this).prev().slideToggle("fast");
                        if($(this).html()==" ▼点击这里,查看提示"){
                            $(this).html(" ▲点击这里,收起提示")
                        }else{
                            $(this).html(" ▼点击这里,查看提示")
                        }
                    });
                });
            </script>
            <!--控制问题标题的位置-->
            <script>
            $(document).ready(function(){
                adjustPositionOfLittleTitle();
            });
                
            function adjustPositionOfLittleTitle(){
                $(".div-littletitle-content").each(function(){
                    $(this).css("left",
                    ($(this).parent().css("width").split("px")[0]-$(this).css("width").split("px")[0])/2
                     );
                });
            }
            </script>
            
            <!--注册类型-->
            <div id="chooseRegisterTypeContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">01.</div>
                        <div class="div-littletitle-content">我想要成为<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            加入科创社可以有两种身份:干事和会员<br>
                            会员:<br>
                            <ul>
                              <li>成为会员无需门槛,或技术基础</li>
                              <li>会员可以享受社团劳动成果</li>
                              <li>会员将拥有社团的一切福利</li>
                            </ul>
                            干事:<br>
                            <ul>
                              <li>可以挑选自己喜欢的部门来共同维持社团</li>
                              <li>大学学习合作的难得机会</li>
                              <li>参与活动筹划和执行</li>
                              <li>挑战自我的机会</li>
                              <li>拥有社团一切福利和特权</li>
                            </ul>
                            考虑好后直接在上面进行选择,如果对我们社团还不了解的话~赶紧用微信搜索"嘉应科创社"关注我们,在那里有详细说明哟~
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <div class="div-radio-bigbutton col-xs-6" id="huiyuan-btn">
                        会员
                    </div>
                    <div class="div-radio-bigbutton col-xs-6" id="ganshi-btn">
                        干事
                    </div>
                    <input id="chooseRegisterTypeInput" style="display:none">
                    <script>
                        $(document).ready(function(){
                            $("#huiyuan-btn").click(function(){
                                $("#errorContainer").html();
                                $("#huiyuan-btn").addClass("div-radio-bigbutton-active");
                                $("#ganshi-btn").removeClass("div-radio-bigbutton-active");
                                $("#chooseRegisterTypeInput").val("会员");
                                
                                $("#nameContainer").css("display","inline");
                                $("#sexContainer").css("display","inline");
                                $("#longtelContainer").css("display","inline");
                                $("#shorttelContainer").css("display","inline");
                                $("#addressContainer").css("display","inline");
                                $("#fromwhereContainer").css("display","inline");
                                $("#qqContainer").css("display","inline");
                                $("#birthdayContainer").css("display","inline");
                                $("#collegeContainer").css("display","inline");
                                
                                
                                $("#knowusfromContainer").css("display","inline");
                                $("#whatwillyoulearnContainer").css("display","inline");
                                $("#handinmoneytypeContainer").css("display","inline");
                                
                                $("#politicalstatusContainer").css("display","none");
                                $("#dutyContainer").css("display","none");
                                $("#departmentContainer").css("display","none");
                                $("#obeydistributeContainer").css("display","none");
                                $("#introduceContainer").css("display","none");
                                $("#departmentyouknowContainer").css("display","none");
                                $("#honorContainer").css("display","none");
                                
                                $("#handinmoneytype-index").html("13.");
                                $("#huiyuanneedtoknow").css("display","inline");
                                $("#ganshineedtoknow").css("display","none");
                                $("#submit-btn").css("display","inline");
                                adjustPositionOfLittleTitle();
                            });
                                
                            $("#ganshi-btn").click(function(){
                                $("#errorContainer").html();
                                $("#ganshi-btn").addClass("div-radio-bigbutton-active");
                                $("#huiyuan-btn").removeClass("div-radio-bigbutton-active");
                                $("#chooseRegisterTypeInput").val("干事");
                                
                                $("#nameContainer").css("display","inline");
                                $("#sexContainer").css("display","inline");
                                $("#longtelContainer").css("display","inline");
                                $("#shorttelContainer").css("display","inline");
                                $("#addressContainer").css("display","inline");
                                $("#fromwhereContainer").css("display","inline");
                                $("#qqContainer").css("display","inline");
                                $("#birthdayContainer").css("display","inline");
                                $("#collegeContainer").css("display","inline");
                                
                                
                                $("#knowusfromContainer").css("display","none");
                                $("#whatwillyoulearnContainer").css("display","none");
                                $("#handinmoneytypeContainer").css("display","inline");
                                
                                $("#politicalstatusContainer").css("display","inline");
                                $("#dutyContainer").css("display","inline");
                                $("#departmentContainer").css("display","inline");
                                $("#obeydistributeContainer").css("display","inline");
                                $("#introduceContainer").css("display","inline");
                                $("#departmentyouknowContainer").css("display","inline");
                                $("#honorContainer").css("display","inline");
                                $("#handinmoneytype-index").html("18.");
                                $("#ganshineedtoknow").css("display","inline");
                                $("#huiyuanneedtoknow").css("display","none");
                                $("#submit-btn").css("display","inline");
                                adjustPositionOfLittleTitle();
                            });
                                
                            
                        });
                    </script>
                </center>
            </div>
            
            <div>
            <p style="">网上报名已经结束咯~~~如果对科创社实在是太感兴趣了~请关注我们的微信"嘉应科创社"</p>
            </div>
            <!--姓名-->
            <div id="nameContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">02.</div>
                        <div class="div-littletitle-content">姓名<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            请正确输入你的姓名<br>
                            注意噢,是姓和名<br>
                            填入网名将报废此表
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <input id="nameInput" class="form-control">
                        </div>

                    </div>
                    <br>

                </center>
            </div>        
            <!--性别-->
            <div id="sexContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">03.</div>
                        <div class="div-littletitle-content">性别<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            性别这个应该不需要提示吧~<br>
                            特殊情况如下:<br>
                            如果你是女汉子请选择女<br>
                            如果你是小受请选择男
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <div class="div-radio-bigbutton col-xs-6" id="boy-btn">
                        男
                    </div>
                    <div class="div-radio-bigbutton col-xs-6" id="girl-btn">
                        女
                    </div>
                    <input id="sexInput" style="display:none">
                    <script>
                        $(document).ready(function(){
                            $("#boy-btn").click(function(){
                                $("#boy-btn").addClass("div-radio-bigbutton-active");
                                $("#girl-btn").removeClass("div-radio-bigbutton-active");
                                $("#sexInput").val("男");
                            });
                                
                            $("#girl-btn").click(function(){
                                $("#girl-btn").addClass("div-radio-bigbutton-active");
                                $("#boy-btn").removeClass("div-radio-bigbutton-active");
                                $("#sexInput").val("女");
                                
                            });
                                
                            
                        });
                    </script>
                </center>
            </div>
            <!--长号-->
            <div id="longtelContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">04.</div>
                        <div class="div-littletitle-content">长号<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            长号将用于加飞信以及活动通知<br>
                            请正确填入你的长号<br>
                            电信/联通卡的同学我们强烈建议你们注册飞信
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <input id="longtelInput" class="form-control">
                        </div>

                    </div>
                    <br>

                </center>
            </div>
            <!--短号-->
            <div id="shorttelContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">05.</div>
                        <div class="div-littletitle-content">短号<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            短号可以方便联系<br>
                            请正确填入你的短号<br>
                            
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <input id="shorttelInput" class="form-control">
                        </div>

                    </div>
                    <br>

                </center>
            </div>
            <!--学校住址-->
            <div id="addressContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">06.</div>
                        <div class="div-littletitle-content">学校住址<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            请填入你在学校的住址<br>
                            例如: 南区8栋502<br>
                            
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <input id="addressInput" class="form-control">
                        </div>

                    </div>
                    <br>

                </center>
            </div>
            <!--籍贯-->
            <div id="fromwhereContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">07.</div>
                        <div class="div-littletitle-content">籍贯<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            请填入你的籍贯<br>
                            例如: 广东 梅州<br>
                            无需详细到家庭地址
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <input id="fromwhereInput" class="form-control">
                        </div>

                    </div>
                    <br>

                </center>
            </div>
            <!--电子邮件-->
            <div id="qqContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">08.</div>
                        <div class="div-littletitle-content">你的QQ<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            请填入正确的qq号码~<br>
                            
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <input id="qqInput" class="form-control">
                        </div>

                    </div>
                    <br>

                </center>
            </div>
            <!--生日-->
            <div id="birthdayContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">09.</div>
                        <div class="div-littletitle-content">生日<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            请填入你的生日<br>
                            例如: 1995.11.15<br>
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <input id="birthdayInput" class="form-control">
                        </div>

                    </div>
                    <br>

                </center>
            </div>
            <!--学院班级-->
            <div id="collegeContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">10.</div>
                        <div class="div-littletitle-content">学院班级<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            你所在的学院加上班级<br>
                            例如: 计算机1304<br>
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <input id="collegeInput" class="form-control">
                        </div>

                    </div>
                    <br>

                </center>
            </div>
            
            <!--会员特有-->
            <!--了解渠道-->
            <div id="knowusfromContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">11.</div>
                        <div class="div-littletitle-content">你是从什么渠道了解到科创社的<must>(多选)</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            你是从哪个渠道认识或者听说我们科创社的呢~<br>
                            我们调查一下哈<br>
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <div class="div-radio-bigbutton col-xs-6 knowusfrom-btn" id="chuandan-btn" value="传单海报">
                        传单海报
                    </div>
                    <div class="div-radio-bigbutton col-xs-6 knowusfrom-btn" id="tongxue-btn" value="同学介绍">
                        同学介绍
                    </div>
                    <div class="div-radio-bigbutton col-xs-6 knowusfrom-btn" id="wangluo-btn" value="网络宣传">
                        网络宣传
                    </div>
                    <div class="div-radio-bigbutton col-xs-6 knowusfrom-btn" id="ziji-btn" value="自己了解">
                        自己了解
                    </div>
                    <div class="div-radio-bigbutton col-xs-6 knowusfrom-btn" id="shixiongshijie-btn" value="师兄师姐介绍">
                        师兄师姐介绍
                    </div>
                    <div class="div-radio-bigbutton col-xs-6 knowusfrom-btn" id="bierenbiwo-btn" value="别人逼我报名">
                        别人逼我报名
                    </div>
                    <div class="div-radio-bigbutton col-xs-12 knowusfrom-btn" id="qita-btn" value="其它">
                        其它
                    </div>
                    <input id="knowusfromInput" style="display:none">
                    <script>
                        $(document).ready(function(){
                            $(".knowusfrom-btn").click(function(){
                                if($(this).attr("class").indexOf("div-radio-bigbutton-active")>=0){
                                    $(this).removeClass("div-radio-bigbutton-active");
                                    var reg=$(this).attr("value")+";";
                                    var strtoreplace = $("#knowusfromInput").val();
                                    $("#knowusfromInput").val(strtoreplace.replace(reg,""));
                                    
                                }else{
                                    $(this).addClass("div-radio-bigbutton-active");
                                    $("#knowusfromInput").val($("#knowusfromInput").val()+$(this).attr("value")+";");
                                    
                                }
                            });
                                
                            
                        });
                    </script>
                </center>
            </div>
            <!--想学什么-->
            <div id="whatwillyoulearnContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">12.</div>
                        <div class="div-littletitle-content">来到科创社最期待学什么<must>(选填)</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            我们会根据这个空所得来的数据适当调整今年活动内容<br>
                            学什么可以填什么?<br>
                            例如:<br>
                            <ul>
                                <li>photoshop</li>
                                <li>flash</li>
                                <li>after effect</li>
                                <li>premiere</li>
                                <li>绘声绘影</li>
                                <li>办公软件</li>
                                <li>电脑知识</li>
                                <li>网络知识</li>
                                <li>等等..只要你想得到的都可以填进来</li>
                            </ul>
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <textarea class="form-control" rows="6" id="whatwillyoulearnInput"></textarea>
                        </div>

                    </div>
                    <br>

                </center>
            </div>
            
            <!--会员特有结束-->
        
        
            <!--干事特有-->
            <!--政治面貌-->
            <div id="politicalstatusContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">11.</div>
                        <div class="div-littletitle-content">政治面貌<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            政治面貌用于了解你们目前的情况<br>
                            例如: 群众/团员/预备党员/党员<br>
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <input id="politicalstatusInput" class="form-control">
                        </div>

                    </div>
                    <br>

                </center>
            </div>
            <!--担任职务-->
            <div id="dutyContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">12.</div>
                        <div class="div-littletitle-content">担任职务<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            你在班里面/学院里面/其他社团里面有没有担任什么职位呢<br>
                            不是你高中的职位哦~是目前的职位<br>
                            不是外面兼职的职位哟~是在学校里面的职位<br>
                            例如: 班长/团学科技部干事/宣传委员/XX社干事<br>
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <input id="dutyInput" class="form-control">
                        </div>

                    </div>
                    <br>

                </center>
            </div>
            <!--我要加入-->
            <div id="departmentContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">13.</div>
                        <div class="div-littletitle-content">竞选部门<must>(可多选)</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            科创社共社六个部门,本空可多选:<br>
                            看看自己对哪个部门感兴趣就可以选哪个<br>
                            部门介绍在微信公众号里面有噢
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <div class="div-radio-bigbutton col-xs-6 department-btn" id="kejibu-btn" value="科技部">
                        科技部
                    </div>
                    <div class="div-radio-bigbutton col-xs-6 department-btn" id="xuanchuanbu-btn" value="宣传部">
                        宣传部
                    </div>
                    <div class="div-radio-bigbutton col-xs-6 department-btn" id="mishubu-btn" value="秘书部">
                        秘书部
                    </div>
                    <div class="div-radio-bigbutton col-xs-6 department-btn" id="zuzhibu-btn" value="组织部">
                        组织部
                    </div>
                    <div class="div-radio-bigbutton col-xs-6 department-btn" id="gongguanbu-btn" value="公关部">
                        公关部
                    </div>
                    <div class="div-radio-bigbutton col-xs-6 department-btn" id="caiwubu-btn" value="财务部">
                        财务部
                    </div>
                    <input id="departmentInput" style="display:none">
                    <script>
                        $(document).ready(function(){
                            $(".department-btn").click(function(){
                                if($(this).attr("class").indexOf("div-radio-bigbutton-active")>=0){
                                    $(this).removeClass("div-radio-bigbutton-active");
                                    var reg=$(this).attr("value")+";";
                                    var strtoreplace = $("#departmentInput").val();
                                    $("#departmentInput").val(strtoreplace.replace(reg,""));
                                    
                                }else{
                                    $(this).addClass("div-radio-bigbutton-active");
                                    $("#departmentInput").val($("#departmentInput").val()+$(this).attr("value")+";");
                                    
                                }
                            });
                                
                            
                        });
                    </script>
                </center>
            </div>
            <!--服从分配-->
            <div id="obeydistributeContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">14.</div>
                        <div class="div-littletitle-content">是否服从分配<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            当部长认为你更适合另一个部门的时候,你是否服从部长的分配,调剂到另外的部门?<br>
                            虽然一般这种事情不会发生<br>
                            但为了公平还是得了解
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <div class="div-radio-bigbutton col-xs-6" id="obey-btn">
                        服从
                    </div>
                    <div class="div-radio-bigbutton col-xs-6" id="disobey-btn">
                        不服从
                    </div>
                    <input id="obeydistributeInput" style="display:none">
                    <script>
                        $(document).ready(function(){
                            $("#obey-btn").click(function(){
                                $("#obey-btn").addClass("div-radio-bigbutton-active");
                                $("#disobey-btn").removeClass("div-radio-bigbutton-active");
                                $("#obeydistributeInput").val("服从");
                            });
                                
                            $("#disobey-btn").click(function(){
                                $("#disobey-btn").addClass("div-radio-bigbutton-active");
                                $("#obey-btn").removeClass("div-radio-bigbutton-active");
                                $("#obeydistributeInput").val("不服从");
                                
                            });
                                
                            
                        });
                    </script>
                </center>
            </div>
            <!--自我介绍-->
            <div id="introduceContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">15.</div>
                        <div class="div-littletitle-content">自我介绍<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            不用太拘谨~尽情释放自己,自我介绍不用太严肃<br>
                            内容尽量多,让我们了解你~<br>
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <textarea class="form-control" rows="6" id="introduceInput"></textarea>
                        </div>

                    </div>
                    <br>

                </center>
            </div>
            <!--对于部门的理解-->
            <div id="departmentyouknowContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">16.</div>
                        <div class="div-littletitle-content">你对于你所竞选的部门的理解<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            这对于社团的管理方式起着重要作用<br>
                            你的理解将成为我们的参考<br>
                            请认真填写此空
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <textarea class="form-control" rows="6" id="departmentyouknowInput"></textarea>
                        </div>

                    </div>
                    <br>

                </center>
            </div>
            <!--获得过的荣誉-->
            <div id="honorContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index">17.</div>
                        <div class="div-littletitle-content">获奖情况<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            可以写从你小学到现在获得过的奖项,大小都可以<br>
                            写越多优势貌似会越多噢<br>
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>
                    
                    
                    <br style="clear:both">
                    <br>
                    <div class="row">
                        <div class="col-xs-10 col-xs-offset-1">
                            <textarea class="form-control" rows="6" id="honorInput"></textarea>
                        </div>

                    </div>
                    <br>

                </center>
            </div>
            <!--干事特有结束-->
        
            <!--交钱-->
            <div id="handinmoneytypeContainer" style="display:none">
                <center>
                    <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                        <div class="div-littletitle-index" id="handinmoneytype-index">13.</div>
                        <div class="div-littletitle-content">上缴会费(￥30)和3张1寸照的方式<must>*</must></div>
                    </div>
                    
                    
                    <div class="tipsOffer col-xs-12 tipsOfferPanel">
                        <div class="div-tipsOffer-content" id="tipsOffer-content">
                            在大学里面,进入任何社团都需要交纳会费<br>
                            科创社的入会费是30元<br>
                            以后除了举行出游活动等大型活动之外,无需再次交钱<br>
                            由于通过网络报名的方式,我们采取了两种交钱的方案<br>
                            <ul>
                            <li>到世纪广场我们的摊位交钱(已结束)</li>
                            <li>我们上门去收~</li>
                            </ul>
                            上门收请确保同学的学校住址写的清晰
                        </div>
                    </div>
                    <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▲略为重要,自动展开,点击收起</div>
                    
                    
                    <div class="div-radio-bigbutton col-xs-12" id="century-btn" style="background-color:#eee;">
                        我去世纪广场交钱和照片(已结束)
                    </div>
                    <div class="div-radio-bigbutton col-xs-12" id="dorm-btn">
                        等师兄师姐来我们宿舍收
                    </div>
                    <input id="handinmoneytypeInput" style="display:none">
                    <script>
                        $(document).ready(function(){
                                
                            $("#dorm-btn").click(function(){
                                $("#dorm-btn").addClass("div-radio-bigbutton-active");
                                $("#handinmoneytypeInput").val("等我们去宿舍收钱");
                                
                            });
                                
                            
                        });
                    </script>
                </center>
            </div>    
        
            
            <div class="tipsOffer col-xs-12 tipsOfferPanel" id="huiyuanneedtoknow" style="display:none">
                <div class="div-tipsOffer-content" id="tipsOffer-content">
                    请仔细阅读以下内容:<br>
                    <ul>
                        <li>本次网络报名所有数据我们将为你保密</li>
                        <li>你所提交的是 会员报名表</li>
                        <li>点击下面的 提交报名表 按钮,你将会得到一个"报名码"</li>
                        <li>报名科创社成为会员需要上缴30块钱入会费和3张1寸照(蓝底红底都可以)</li>
                        <li>如果你上面勾选的是到世纪广场上交材料,请于10月18号早上或者下午两个时间段到世纪广场凭着"报名码"上交</li>
                        <li>如果勾选的是等待师兄师姐上门收材料,请</li>
                        <li>上门收材料的师兄师姐会佩戴工作证,穿社服,谨防受骗,记得要和ta比对"报名码"才能上交</li>
                        <li>当你收到科创社的飞信添加通知的时候,你就成功地成为了科创社会员啦~</li>
                    </ul>
                </div>
            </div>
        
            <div class="tipsOffer col-xs-12 tipsOfferPanel" id="ganshineedtoknow" style="display:none">
                <div class="div-tipsOffer-content" id="tipsOffer-content">
                    请仔细阅读以下内容:<br>
                    <ul>
                        <li>此次报名将从10月18号开启,19号凌晨结束</li>
                        <li>本次网络报名所有数据我们将为你保密</li>
                        <li>你所提交的是 干事报名表</li>
                        <li>点击下面的 提交报名表 按钮,你将会得到一个"报名码"</li>
                        <li>报名科创社成为会员需要上缴30块钱入会费和3张1寸照(蓝底红底都可以)</li>
                        <li>如果你上面勾选的是到世纪广场上交材料,请于10月18号早上或者下午两个时间段到世纪广场凭着"报名码"上交</li>
                        <li>如果勾选的是等待师兄师姐上门收材料,我们将于10月19号上门收</li>
                        <li>上门收材料的师兄师姐会佩戴工作证,穿社服,谨防受骗,记得要和ta比对"报名码"才能上交</li>
                        <li>请准干事们于10月26日到我们的指定地点参与面试竞选,地点待公布</li>
                        <li>面试内容为:自我介绍,以及部长提问~不用紧张哈</li>
                    </ul>
                </div>
            </div>
            
            <div class="div-radio-submitbutton col-xs-12" id="submit-btn" style="display:none">
                        提交报名表
            </div>
        
            <script>
                $(document).ready(function(){
                    
                    var dates = new Date();
                    if(dates.getFullYear() == "2014" && dates.getMonth()+1 == "10" && dates.getDate() == "18"){
                        $("#submit-btn").css("display","inline");
                    }else{
                        $("#submit-btn").css("display","none");
                    }
                    
                    $("#submit-btn").click(function(){
                        $("#errorContainer").html("");
                        $.ajax({
                            url: "<?php echo PAGE_PATH;?>php/joinusajax.php",
                            type: "POST",
                            data:{
                                'what':'submit',
                                'registertype':$('#chooseRegisterTypeInput').val(),
                                'name':$('#nameInput').val(),
                                'sex':$('#sexInput').val(),
                                'longtel':$('#longtelInput').val(),
                                'shorttel':$('#shorttelInput').val(),
                                
                                'address':$('#addressInput').val(),
                                'fromwhere':$('#fromwhereInput').val(),
                                'qq':$('#qqInput').val(),
                                'birthday':$('#birthdayInput').val(),
                                'college':$('#collegeInput').val(),
                                
                                'knowusfrom':$('#knowusfromInput').val(),
                                'whatwillyoulearn':$('#whatwillyoulearnInput').val(),
                                'politicalstatus':$('#politicalstatusInput').val(),
                                'duty':$('#dutyInput').val(),
                                'department':$('#departmentInput').val(),
                                
                                'obeydistribute':$('#obeydistributeInput').val(),
                                'introduce':$('#introduceInput').val(),
                                'departmentyouknow':$('#departmentyouknowInput').val(),
                                'honor':$('#honorInput').val(),
                                'handinmoneytype':$('#handinmoneytypeInput').val()
                                
                            
                            },
                            success: function(data,status){
                                if(data.split(":::")[0].indexOf("error")>=0){
                                    var content = data.split(":::")[2];
                                    $("#errorContainer").html("<p class=\"bg-danger p_errorinfo\"><span class=\"glyphicon glyphicon-remove\"></span>&nbsp;&nbsp;"+content+"</p>");
                                }else if(data.split(":::")[0].indexOf("success")>=0){
                                    if(data.split(":::")[1].indexOf("submitOk")>=0){
                                        window.location.href = "registercode"+data.split(":::")[2];
                                    }
                                }
                            }
                        });
                        
                    });                  
                });
            </script>
            <!--记录错误-->
            <br style="clear:both">
            <div id="errorContainer">
            </div>
            <br style="clear:both">
            <br>
            <br>
            <br>
            
            
        </div>
    </div>

</body>

</html>