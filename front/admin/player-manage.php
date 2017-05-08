<!DOCTYPE html>
<html>

<head>
    <title>玩家管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">

    <!-- build:js /assets/js/admin/player-manage.js -->
    <script src="/front/assets/js/jquery-1.12.4.js"></script>
    <script src="/front/assets/js/bootstrap.js"></script>
    <!-- endbuild -->

    <!-- build:css /assets/css/admin/player-manage.css -->
    <link href="/front/assets/style/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="/front/assets/style/font-awesome.css">
    <!-- endbuild -->

</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>已经注册账号<small>(今年的)</small></h1>
    </div>
    <p>当前已经注册的人总数:<?php
        $year = date("Y");
        $nc = new sqlihelper();
        if(!$nc->connect()){
        }
        $result = $nc ->mysql("select id from question_people where lastactiveyear='$year'");
        echo $result->num_rows;
        ?>
    </p>
    <div class="panel panel-default">
        <div class="panel-heading">查找某个人的统计情况</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-6">
                    <input type="text" class="form-control" placeholder="账号的姓名" id="findname">
                </div><!-- /.col-lg-6 -->
                <div class="col-lg-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="账号的联系方式" id="findtel">
                        <span class="input-group-btn">
                        <button class="btn btn-default" onclick="findprofile()">查询个人资料</button>
                      </span>
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
            </div><!-- /.row -->
            <br>
            <div class="alert alert-danger" role="alert" id="findtips" style="display:none"></div>

        </div>
    </div>



    <br>



    <div class="page-header">
        <h1>被封禁账号<small>(验证码输错了5次以上的)</small></h1>
    </div>
    <br>
    <table class="table table-striped">
        <tr>
            <th>#</th>
            <th>姓名</th>
            <th>联系方式</th>
            <th>操作</th>
        </tr>
        <?php
        $result = $nc ->mysql("select id,name,tel from question_people where isbanned=1 and lastactiveyear = '{$year}'");
        if($result->num_rows == 0){
            ?>
            <tr>
                <td colspan="4"><span class="fa fa-minus-circle"></span>&nbsp;&nbsp;没有任何人被封号</td>
            </tr>
            <?php
        }
        while($row = $result->fetch_assoc()){
            ?>
            <tr>
                <td><?php echo $row['id'];?></td>
                <td id="deban-name-<?php echo $row['id'];?>"><?php echo $row['name'];?></td>
                <td id="deban-tel-<?php echo $row['id'];?>"><?php echo $row['tel'];?></td>
                <td>
                    <button class="btn btn-success" onclick="deban('<?php echo $row['id'];?>')">解封</button>
                    <button class="btn btn-default" onclick="jumptoprofile('<?php echo $row['id'];?>')">跳转到个人资料</button>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>



    <div class="page-header">
        <h1>消极比赛判定<small>(消极比赛)</small></h1>
    </div>
    <br>
    <table class="table table-striped">
        <tr>
            <th>#</th>
            <th>姓名</th>
            <th>消极次数</th>
            <th>平均延迟(s)</th>
            <th>最大延迟(s)</th>
            <th>操作</th>
        </tr>
        <?php
        $result = $nc ->mysql("select  name,active,peopleid,delay,avg(delay),max(delay) from question_idcinputtime,question_people where delay>0 and lastactiveyear = '{$year}' and question_people.id = question_idcinputtime.peopleid group by peopleid order by avg(delay) desc");
        if($result->num_rows == 0){
            ?>
            <tr>
                <td colspan="4"><span class="fa fa-minus-circle"></span>&nbsp;&nbsp;没有任何人有消极比赛嫌疑</td>
            </tr>
            <?php
        }
        while($row = $result->fetch_assoc()){
            ?>
            <tr>
                <td><?php echo $row['peopleid'];?></td>
                <td><?php echo $row['name'];?></td>
                <td><?php echo $row['active'];?></td>
                <td><?php echo $row['avg(delay)'];?></td>
                <td><?php echo $row['max(delay)'];?></td>
                <td>
                    <button class="btn btn-danger" onclick="passive('<?php echo $row['peopleid'];?>')">判定消极</button>
                    <button class="btn btn-default" onclick="jumptoprofile('<?php echo $row['peopleid'];?>')">跳转到个人资料</button>
                </td>
            </tr>
            <?php
        }
        $nc->close();
        ?>
    </table>
    <br>







</div>


<div class="modal fade" id="confirmmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modaltitle">Modal title</h4>
            </div>
            <div class="modal-body">
                <p id="modalcontent">One fine body&hellip;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="sendope()">确定</button>
            </div>
        </div>
    </div>
</div>


<script>
    var opetype = "";
    var peopleid = 0;
    //解封账号
    function deban(id){
        opetype = "deban";
        peopleid = id;
        $("#confirmmodal").modal('show');
        $("#modaltitle").html('解封确认');
        $("#modalcontent").html('确定要解封&nbsp;&nbsp;<strong>'+$("#deban-name-"+id).html()+'<small>('+$("#deban-tel-"+id).html()+')</small></strong>&nbsp;&nbsp;吗?');
    }


    function passive(id){
        opetype = "passive";
        peopleid = id;
        $("#confirmmodal").modal('show');
        $("#modaltitle").html('消极确认');
        $("#modalcontent").html('确定要为&nbsp;&nbsp;<strong>'+$("#deban-name-"+id).html()+'<small>('+$("#deban-tel-"+id).html()+')</small></strong>&nbsp;&nbsp;添加一次消极次数吗?');
    }



    function sendope(){
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/manage-playerdata",
            type: "POST",
            data:{
                'what': opetype,
                'peopleid': peopleid
            },
            success: function(data,status){
                data = JSON.parse(data);
                $("#Operatetips").html(data.info);
                if(data.code == 0000){
                    location.reload();
                }
            }
        });
    }


    function jumptoprofile(id){
        location.href = "./player-profile?t="+id;
    }

    function findprofile(){
        $.ajax({
            url: "<?php echo ROOT_PREFIX.API;?>/manage-playerdata",
            type: "POST",
            data:{
                'what': 'findprofile',
                'name': $("#findname").val(),
                'tel': $("#findtel").val()
            },
            success: function(data,status){
                data = JSON.parse(data);

                if(data.code == 0000){
                    jumptoprofile(data.id);
                }else {
                    $("#findtips").css("display","block");
                    $("#findtips").html(data.info);
                    setTimeout(function(){$("#findtips").css("display","none");},5000);
                }
            }
        });
    }
</script>

</body>
</html>