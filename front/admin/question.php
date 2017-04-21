<?php
/**
 * User: trickyEdecay
 * Date: 2017/4/18
 * Time: 1:54
 */
?>
<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">

    <!-- build:js /assets/js/admin/question.js -->
    <script src="/front/assets/js/jquery-1.12.4.js"></script>
    <script src="/front/assets/js/bootstrap.js"></script>
    <!-- endbuild -->

    <!-- build:css /assets/css/admin/question.css -->
    <link href="/front/assets/style/bootstrap.css" rel="stylesheet">
    <!-- endbuild -->

    <script>
        $(document).ready(function(){
            clearAllInput();

            $("#submit").click(function(){
                submitQuestion();
            });
        });

        function clearAllInput(){
            $("#questionInput").val("");
            $("#AInput").val("");
            $("#BInput").val("");
            $("#CInput").val("");
            $("#DInput").val("");
            $("#peoplelimitInput").val("");
            $("#addscoreInput").val("");
            $("#minusscoreInput").val("");
            $("#sortInput").val("");
            $("#availabletimeInput").val("");
            $("#opeInput").val("add");
            $("#indexInput").val("");
        }

        function submitQuestion(){
            $.ajax({
                url: "<?php echo ROOT_PREFIX.API;?>/CAQ_function",
                type: "POST",
                data:{
                    'what':'submit',
                    'question':$('#questionInput').val(),
                    'a':$('#AInput').val(),
                    'b':$('#BInput').val(),
                    'c':$('#CInput').val(),
                    'd':$('#DInput').val(),

                    'peoplelimit':$('#peoplelimitInput').val(),
                    'addscore':$('#addscoreInput').val(),
                    'minusscore':$('#minusscoreInput').val(),
                    'sort':$('#sortInput').val(),
                    'availabletime':$('#availabletimeInput').val(),
                    'ope':$('#opeInput').val(),
                    'id':$("#indexInput").val()
                },
                success: function(data,status){
                    data = JSON.parse(data);
                    if(data.code == 0000){
                        location.reload();

                    }
                }
            });
        }


        function getModifyInfo(index){
            $.ajax({
                url: "<?php echo ROOT_PREFIX.API;?>/CAQ_function",
                type: "POST",
                data:{
                    'what':'getModifyInfo',
                    'id':index
                },
                success: function(data,status){
                    console.log(data);
                    data = JSON.parse(data);
                    if(data.code == 0000){
                        $('#questionInput').val(data.question);
                        $('#AInput').val(data.a);
                        $('#BInput').val(data.b);
                        $('#CInput').val(data.c);
                        $('#DInput').val(data.d);

                        $('#peoplelimitInput').val(data.peoplelimit);
                        $('#addscoreInput').val(data.addscore);
                        $('#minusscoreInput').val(data.minusscore);
                        $('#sortInput').val(data.sort);
                        $('#availabletimeInput').val(data.availabletime);
                        $("#indexInput").val(data.id);
                    }
                }
            });
        }

        function modify(index){
            getModifyInfo(index);
            $('#opeInput').val('edit');
            $('#whattodo').html('修改题目');
        }

        function deleteid(index){
            $.ajax({
                url: "<?php echo ROOT_PREFIX.API;?>/CAQ_function",
                type: "POST",
                data:{
                    'what':'deleteid',
                    'id':index
                },
                success: function(data,status){
                    data = JSON.parse(data);
                    if(data.code == 0000){
                        location.reload();

                    }
                }
            });
        }
    </script>
</head>

<body>
<div class='container mainContainer'>
    <div class="col-md-12 col-sm-12 col-xs-12 div_mainblock">
        <center>
            <div class="panel panel-default">
                <div class="panel-body">
                    <center><h1>简陋的后台将就着用哈</h1></center><br><br><br>
                    <center><p id="whattodo">插入题目</p></center>

                    <div>
                        <div class="form-group col-md-12 col-sm-1 col-xs-1">
                            <label for="questionInput">题目</label>
                            <input class="form-control" id="questionInput" placeholder="输入一个问题">
                        </div>

                        <div class="form-group col-md-12 col-sm-1 col-xs-1">
                            <label for="AInput">正确的答案</label>
                            <input class="form-control" id="AInput" placeholder="正确的答案">
                        </div>

                        <div class="form-group col-md-12 col-sm-1 col-xs-1">
                            <label for="BInput">错误的答案</label>
                            <input class="form-control" id="BInput" placeholder="错误的答案">
                        </div>

                        <div class="form-group col-md-12 col-sm-1 col-xs-1">
                            <label for="CInput">错误的答案</label>
                            <input class="form-control" id="CInput" placeholder="错误的答案">
                        </div>

                        <div class="form-group col-md-12 col-sm-1 col-xs-1">
                            <label for="DInput">错误的答案</label>
                            <input class="form-control" id="DInput" placeholder="错误的答案">
                        </div>

                        <div class="form-group col-md-3 col-sm-1 col-xs-1">
                            <label for="peoplelimitInput">允许多少人进入此题目</label>
                            <input class="form-control" id="peoplelimitInput" placeholder="1<=x<=400">
                        </div>

                        <div class="form-group col-md-3 col-sm-1 col-xs-1">
                            <label for="addscoreInput">加多少分</label>
                            <input class="form-control" id="addscoreInput" placeholder="合理设置哦~">
                        </div>

                        <div class="form-group col-md-3 col-sm-1 col-xs-1">
                            <label for="minusscoreInput">扣多少分</label>
                            <input class="form-control" id="minusscoreInput" placeholder="合理设置哦~">
                        </div>

                        <div class="form-group col-md-3 col-sm-1 col-xs-1">
                            <label for="sortInput">题目排序</label>
                            <input class="form-control" id="sortInput" placeholder="题目的排序,大的靠后~">
                        </div>

                        <div class="form-group col-md-3 col-sm-1 col-xs-1">
                            <label for="sortInput">此题允许秒数</label>
                            <input class="form-control" id="availabletimeInput" placeholder="此题目的允许秒数控制">
                        </div>

                        <div class="form-group" style="display:none">
                            <label for="opeInput">行为</label>
                            <input class="form-control" id="opeInput">
                        </div>

                        <div class="form-group" style="display:none">
                            <label for="indexInput">题目ID</label>
                            <input class="form-control" id="indexInput">
                        </div>
                        <br style="clear:both;">
                        <button class="btn btn-default" id="submit">提交</button>
                    </div>
                </div>
            </div>





            <br>
            <p>--------------我是一条可爱的分割线--------------</p>
            <br>

            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>#</th>
                        <th>题目排序</th>
                        <th>加分</th>
                        <th>扣分</th>
                        <th>允许人数</th>
                        <th>操作</th>
                        <th>题目</th>
                    </tr>
                    <?php
                    $nc = new sqlhelper();
                    $opecode = $nc->connect();
                    if($opecode!=0){

                    }
                    $result = $nc ->mysql("select * from question order by sort");
                    while($row = mysql_fetch_array($result)){
                        ?>
                        <tr>
                            <td><?php echo($row['id']);?></td>
                            <td><?php echo($row['sort']);?></td>
                            <td><?php echo($row['addscore']);?></td>
                            <td><?php echo($row['minusscore']);?></td>
                            <td><?php echo($row['peoplelimit']);?></td>
                            <td><a onclick="modify(<?php echo($row['id']);?>)">修改</a>&nbsp;&nbsp;&nbsp;<a onclick="deleteid(<?php echo($row['id']);?>)">删除</a></td>
                            <td><?php echo($row['question']);?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>




        </center>
    </div>
</div>


</body>

</html>