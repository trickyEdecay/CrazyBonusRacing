<?php 
require_once("../config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');
?>

<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>caq.less">
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>test.less">
    <?php echo LESS_DEFINE; ?>
    <?php echo constant( 'JQuery_DEFINE');echo constant( 'BOOTSTRAP_DEFINE');?>
    
    <script>
    function changequestionto(id){
        $.ajax({
            url: "<?php echo PAGE_PATH;?>php/CAQ_function.php",
            type: "POST",
            data:{
                'what':'changequestionto',
                'questionid':id
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips"+id).html(data.info);
                if(data.code == 0000){
                    location.reload();
                }
            }
        });
    }
        
    function showquestion(id){
        $.ajax({
            url: "<?php echo PAGE_PATH;?>php/CAQ_function.php",
            type: "POST",
            data:{
                'what':'showquestion',
                'questionid':id
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips"+id).html(data.info);
                if(data.code == 0000){
                    location.reload();
                }
            }
        });
    }
    
    function showkey(id){
        $.ajax({
            url: "<?php echo PAGE_PATH;?>php/CAQ_function.php",
            type: "POST",
            data:{
                'what':'showkey',
                'questionid':id
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips"+id).html(data.info);
                if(data.code == 0000){
                    location.reload();
                }
            }
        });
    }
        
    function showsponser(id){
        $.ajax({
            url: "<?php echo PAGE_PATH;?>php/CAQ_function.php",
            type: "POST",
            data:{
                'what':'showsponser',
                'questionid':id
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips"+id).html(data.info);
                if(data.code == 0000){
                    location.reload();
                }
            }
        });
    }
        
    function togglereging(){
        $.ajax({
            url: "<?php echo PAGE_PATH;?>php/CAQ_function.php",
            type: "POST",
            data:{
                'what':'showprize',
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips1").html(data.info);
                if(data.code == 0000){
                    location.reload();
                }
            }
        });
    }
        
        
    function togglereging(changeto){
        $.ajax({
            url: "<?php echo PAGE_PATH;?>php/CAQ_function.php",
            type: "POST",
            data:{
                'what':'togglereging',
                'changeto':changeto
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips1").html(data.info);
                if(data.code == 0000){
                    location.reload();
                }
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
          <div class="panel-heading"><?php echo $title.$row['sort'].".".$row['question'];?></div>
          <div class="panel-body">
            <?php
              if($title == "(当前) "){
                  $results = $ncs->mysql("select * from question_buffer where questionid = '{$row['id']}' and state='joined'");
                  $peoplehavegotincount = mysql_num_rows($results);
                  $results = $ncs->mysql("select * from question_buffer where questionid = '{$row['id']}' and state='done'");
                  $peoplehavedonecount = mysql_num_rows($results);
              ?>
              <p>已经有 <?php echo $peoplehavegotincount; ?> 人进入该题目,目前有 <?php echo $peoplehavedonecount; ?> 人完成了答题</p>
              <?php
              }
            ?>
            <p id="Operatetips<?php echo $row['id'];?>"></p>
            <button class="col-md-3 col-sm-12 col-xs-12 btn btn-default btn-lg" onclick="showsponser(<?php echo $row['id'];?>)">切换并赞助商页面</button>
              <br style="clear:both">
              <br style="clear:both">
            <button class="col-md-3 col-sm-12 col-xs-12 btn btn-default btn-lg" onclick="changequestionto(<?php echo $row['id'];?>)">显示此题验证码</button>
              <br style="clear:both">
              <br style="clear:both">
            <button class="col-md-3 col-sm-12 col-xs-12 btn btn-default btn-lg" onclick="showquestion(<?php echo $row['id'];?>)">显示题目和选项</button>
              <br style="clear:both">
              <br style="clear:both">
            <button class="col-md-3 col-sm-12 col-xs-12 btn btn-default btn-lg" onclick="showkey(<?php echo $row['id'];?>)">显示答案并结算得分</button>
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
    </div>
</body>

</html>