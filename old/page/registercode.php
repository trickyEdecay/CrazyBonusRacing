<?php 
require_once("config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');
?>
<?php
if(is_array($_GET)&&count($_GET)>0){ 
    if(isset($_GET["code"])){ 
        $code=$_GET["code"];//存在 
    }else{
        $code="";
    }
}else{
    $code="";
}
?>
<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <?php echo constant( 'JQuery_DEFINE');echo constant( 'BOOTSTRAP_DEFINE');?>
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>common.less">
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>joinus.less">
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>registercode.less">
    <?php echo LESS_DEFINE; ?>
</head>

<body>
    <div class='container mainContainer'>
        <?php
        if($code!=""){
            $nc = new sqlhelper();
            $opecode = $nc->connect();
            if($opecode!=0){
                die();
            }
            $code = mysql_real_escape_string($code);
            $result = $nc->mysql("select name,formstatus,submittime from registration_info where handinmoneykey = '$code'");
            if(mysql_num_rows($result)<=0){
                die();   
            }
            $row = mysql_fetch_array($result);
            $name = $row['name'];
            $formstatus = $row['formstatus'];
            $submittime = $row['submittime'];
        ?>
        <div class="col-md-12 col-sm-12 col-xs-12 div_registercodeContainer">
            <h4>你的报名码:</h4>
            <h2><?php echo $code; ?></h2>
            <h4 class="h4_status">状态: <?php echo $formstatus;?></h4>
            <h4 class="h4_status">报名人: <?php echo $name;?></h4>
            <h4 class="h4_status">报名表提交时间: <?php echo $submittime;?></h4>
        </div>
        <br style="clear:both">
        <div class="col-md-12 col-sm-12 col-xs-12 div_mainblock">
            <center>
                <div class="col-md-12 col-sm-12 col-xs-12 div_aboutthecode">
                    <h5>
                        请牢牢记住你的报名码~<br>
                        存在你的手机或者写在纸上<br>
                        当然,浏览器可能会自动为您自动保留数据<br>
                        <br><br>
                        <coffee>这个报名码的作用:</coffee><br><br>
                        <ul>
                            <li>通过网络进行报名的同学可通过此号码得知自己是否已经成为科创社成员;</li>
                            <li>交钱时请比对报名码,防止受骗;</li>
                            <li>入会后,无需再记住此报名码,因为他没有其它的作用.</li>
                        </ul>
                    </h5>
                    
                </div>
            </center>
                    <p class="font-lightcoffee p_technioffer">嘉应科创社网页技术 @2014</p>
        </div>
        <?php
        }else{
            if(isset($_COOKIE['ukey'])){ //先判断cookie存在与否
                list($name, $submittime,$handinmoneykey) = explode(':::', $_COOKIE['ukey']);
                ?>
                <script type="text/javascript">
                    window.location.href = "registercode<?php echo $handinmoneykey; ?>";
                </script>
                <?php
            }
        ?>
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
                
                $("#submit-btn").click(function(){
                    if($("#registerCode").val()==""){
                        $("#errorContainer").html("注册码不能为空哟~");
                    }else{
                        if(/KCS-([a-zA-Z0-9]{8})/.test($("#registerCode").val())){
                            window.location.href = "registercode"+$("#registerCode").val();   
                        }else{
                            $("#errorContainer").html("注册码的格式不正确哟~");
                        }
                    }
                });
            });
        </script>
        
        <div class="col-md-12 col-sm-12 col-xs-12 div_mainblock">
            <center>
                <br>
                <br>
                <br>
                <br>
                
                <div class="div-littletitle color-white-gray-20 font-comfortableblack">
                    <div class="div-littletitle-index">#</div>
                    <div class="div-littletitle-content">请输入你的报名码:</div>
                </div>


                <div class="tipsOffer col-xs-12 tipsOfferPanel" style="display:none">
                    <div class="div-tipsOffer-content" id="tipsOffer-content">
                        没有报名码?<br>
                        没有报名码的话要先填表哟~<br>
                        输入报名码后网页将告诉你你的报名情况哟
                    </div>
                </div>
                <div class="tipsOffer col-xs-12 tipsOfferFlip"> ▼点击这里,查看提示</div>


                <br style="clear:both">
                <br>
                <div class="row">
                    <div class="col-xs-10 col-xs-offset-1">
                        <input class="form-control col-md-6 col-sm-6 col-xs-12" id="registerCode" placeholder="请正确输入你的报名码">
                    </div>

                </div>
                
                <br>
                <div class="div-radio-submitbutton col-xs-12" id="submit-btn">
                    确定
                </div>
                <!--记录错误-->
                <br style="clear:both">
                <div id="errorContainer">
                </div>
                <br style="clear:both">
                    
            </center>
                    <p class="font-lightcoffee p_technioffer">嘉应科创社网页技术 @2014</p>
        </div>
        <?php
        }
        ?>
    </div>


</body>

</html>