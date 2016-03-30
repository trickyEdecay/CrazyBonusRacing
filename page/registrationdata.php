<?php 
require_once("config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');
?>

<?php
if(is_array($_GET)&&count($_GET)>0){ 
    if(isset($_GET["t"])){ 
        $code=$_GET["t"];//存在 
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
    <title>---</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>test.less">
    <?php echo LESS_DEFINE; ?>
    <?php echo constant( 'JQuery_DEFINE');echo constant( 'BOOTSTRAP_DEFINE');?>
</head>

<body>
    <div class='container mainContainer'>
        <div class="col-md-12 col-sm-12 col-xs-12 div_mainblock">
<?php
if($code=="kechuangshe"){
?>
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <th>#</th>
                        <th>姓名</th>
                        <th>注册类型</th>
                        <th>交钱方式</th>
                        <th>报名码</th>
                        <th>性别</th>
                        <th>长号</th>
                        <th>短号</th>
                        <th>住址</th>
                        <th>qq</th>
                        <th>生日</th>
                        <th>学院班级</th>
                        <th>政治面貌</th>
                        <th>家乡</th>
                        <th>了解我们的途径</th>
                        <th>担任职位</th>
                        <th>获奖情况</th>
<!--                        <th>自我介绍</th>-->
                        <th>所选部门</th>
                        <th>服从分配</th>
<!--                        <th>对于部门的理解</th>-->
                        <th>报名表提交时间</th>
                        <th>报名表状态</th>
                    
                    </tr>
                <?php
                    $nc = new sqlhelper();
                    $opecode = $nc->connect();
                    if($opecode!=0){
                        die();
                    }
                    $result = $nc->mysql("select * from registration_info");
                    $totalCount = mysql_num_rows($result);
                    while($row = mysql_fetch_array($result)){
                        ?>
                    <tr>
                        <td><?php echo $row['id'];?></td>
                        <td><?php echo $row['name'];?></td>
                        <td><?php echo $row['registertype'];?></td>
                        <td><?php echo $row['handinmoneytype'];?></td>
                        <td><?php echo $row['handinmoneykey'];?></td>
                        <td><?php echo $row['sex'];?></td>
                        <td><?php echo $row['longtel'];?></td>
                        <td><?php echo $row['shorttel'];?></td>
                        <td><?php echo $row['address'];?></td>
                        <td><?php echo $row['qq'];?></td>
                        <td><?php echo $row['birthday'];?></td>
                        <td><?php echo $row['college'];?></td>
                        <td><?php echo $row['politicalstatus'];?></td>
                        <td><?php echo $row['fromwhere'];?></td>
                        <td><?php echo $row['knowusfrom'];?></td>
                        <td><?php echo $row['duty'];?></td>
                        <td><?php echo $row['honor'];?></td>
<!--                        <td><?php echo $row['introduce'];?></td>-->
                        <td><?php echo $row['department'];?></td>
                        <td><?php echo $row['obeydistribute'];?></td>
<!--                        <td><?php echo $row['departmentyouknow'];?></td>-->
                        <td><?php echo $row['submittime'];?></td>
                        <td><?php echo $row['formstatus'];?></td>
                    </tr>
                    <?php
                    }
                    $result = $nc->mysql("select * from registration_info where registertype='干事'");
                    $ganshiCount = mysql_num_rows($result);
                    $result = $nc->mysql("select * from registration_info where registertype='会员'");
                    $huiyuanCount = mysql_num_rows($result);
                    echo "<div class=\"well\">报名表总数:$totalCount 张,干事表:$ganshiCount 张,会员表:$huiyuanCount 张</div>";
                ?>
                </tbody>
            </table>
            
            
<?php
}
?>
        </div>
    </div>


</body>

</html>