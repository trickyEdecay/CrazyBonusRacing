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
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>test.less">
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>caq.less">
    <?php echo LESS_DEFINE; ?>
    <?php echo constant( 'JQuery_DEFINE');echo constant( 'BOOTSTRAP_DEFINE');?>
    <script>
        $(document).ready(function(){

        });

        function login(){
            if($("#loginbtn").html()=="进入疯狂抢答"){
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
                        }
                    }
                });
            }
        }
        
        function register(){
            if($("#registerbtn").html()=="登记并进入疯狂抢答"){
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
                        }
                    }
                });
            }
        }
    </script>
</head>

<body  style="background-color:#eee">
    <img src="http://cdlku.img44.wal8.com/img44/517420_20150507182110/143099413858.png" class="img-responsive" alt="Responsive image" />
<!--    <img src="/page/img/banner_quanmindafengqiang.png" class="img-responsive" alt="Responsive image">-->
    <div class='container mainContainer'>
        <div class="col-md-12 col-sm-12 col-xs-12 div_mainblock">
            <center>
                <br>
                <br>
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
                $result = $nc->mysql("select * from question_config where keyname='isreging'");
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
                <p class="p-bottom-extras">科创社 2014 技术提供</p>
            </center>
        </div>
    </div>


</body>

</html>