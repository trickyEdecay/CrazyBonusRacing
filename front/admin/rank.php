<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">

    <!-- build:js /assets/js/admin/rank.js -->
    <script src="/front/assets/js/jquery-1.12.4.js"></script>
    <script src="/front/assets/js/bootstrap.js"></script>
    <!-- endbuild -->

    <!-- build:css /assets/css/admin/rank.css -->
    <link href="/front/assets/style/rank.less" rel="stylesheet">
    <!-- endbuild -->
</head>
<body id="maincontainer" style="background-color:#ddd">
    <div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <br>
            
            <div class="div-top-block">
                <center>
                    <div class="div-top-title"><span class="glyphicon glyphicon-sort-by-attributes"></span>&nbsp;&nbsp;获奖名单</div>
                </center>
                <?php
                    $nc = new sqlhelper();
                    $opecode = $nc->connect();
                    if($opecode!=0){
                        die();
                    }
                    $index=0;
                    $limitsum = 1000;
                    $currentsum = 0;
                    $prize = 0;
                    $year = date("Y");
                    $result = $nc->mysql("select score,name from question_people where isbanned=0 and lastactiveyear='$year' order by ranking");
                    while($row = mysql_fetch_array($result)){
                        if($currentsum+$row['score']<=$limitsum){
                            $currentsum=$currentsum+$row['score'];
                            $prize =$row['score'];
                        }else{
                            if($currentsum<$limitsum){
                                $prize=$limitsum-$currentsum;
                                $currentsum=$limitsum;
                            }else{
                                break;
                            }
                        }
                        $index++;
                ?>
                <div class="div-top-row">
                    <div style="position:relative;height:45px;">
                        <div class="div-top-index-circle"><div class="div-top-index-number"><?php echo $index;?></div></div><span class="div-top-name">&nbsp;&nbsp;<?php echo $row['name']." - {$prize} 元";?></span>
                        <br style="clear:both">
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>
</body>

</html>