<?php 
require_once("../../../config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');
?>

<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <?php echo constant( 'JQuery_DEFINE');echo constant( 'BOOTSTRAP_DEFINE');?>
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>admin_weixin_editor.less">
    <?php echo LESS_DEFINE; ?>
</head>

<body>
    <div class='container mainContainer'>
        <div class="col-md-12 col-sm-12 col-xs-12 div_mainblock">
            
            <div class="row">
                <!--模板栏-->
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <h1>模板</h1>
                    <hr/>
                    <div class="div-moduleblock-container">
                        
                        <div class="div-moduleblock"  id="module-maintitle">
                            <br>
                            <h1 style="color:#333;font-family:微软雅黑,黑体,宋体">正文标题</h1>
                            <br>
                        </div>
                        
                        <hr/>
                        <div class="div-moduleblock" >
                            <span style="display:inline-block;width:100%;height:2px;background-color:#ddd;"></span>
                        </div>
                        <hr/>
                        <div class="div-moduleblock" id="module-littletitle">
                            <br>
                            <p style="color:#825930;font-size:18px;font-family:微软雅黑,黑体,宋体;border-left: 5px solid #ffb90d;padding-left:10px;">小标题</p>
                            <br>
                        </div>
                        
                        <hr/>
                        <div class="div-moduleblock">
                            <br>
                            <p style="color:#333;font-size:16px;font-family:微软雅黑,黑体,宋体">正文内容</p>
                            <br>
                        </div>
                        <hr/>
                        <div class="div-moduleblock">
                            <br>
                            <p style="color:#777;font-size:16px;font-family:微软雅黑,黑体,宋体">辅助内容</p>
                            <br>
                        </div>
                        
                        <hr/>
                        <div class="div-moduleblock">
                            <br>
                            <span style="color:#825930;background-color:#FFF6B5;border-radius:5px;padding:2px 4px;font-size:16px;font-family:微软雅黑,黑体,宋体">重点内容</span>
                            <br>
                        </div>
                        
                        <hr/>
                        <div class="div-moduleblock">
                            <br>
                            <center>
                            <p><span style="box-sizing: border-box;color:#fff;background-color:#FFB90D;border-radius:50px;padding:8px 16px;font-size:16px;font-family:微软雅黑,黑体,宋体">居中标题</span></p>
                            </center>
                            <br>
                        </div>
                        
                        <hr/>
                        <div class="div-moduleblock">
                            <br>
                            <p style="color:#825930;background-color:#FFF6B5;border:1px solid #FFB90D; border-radius:10px;padding:10px;font-size:16px;font-family:微软雅黑,黑体,宋体">题外话,补充说明</p>
                            <br>
                        </div>
                        
                        <hr/>
                        <div class="div-moduleblock">
                            <br>
                            <p><span style="color:#fff;background-color:#FFB90D;border-radius:10px 10px 0 0;padding:8px 16px;font-size:16px;font-family:微软雅黑,黑体,宋体">带框说明</span></p>
                            <p style="color:#825930;background-color:#FFF6B5;border:1px solid #FFB90D; border-radius:0 10px 10px 10px;padding:10px;font-size:16px;font-family:微软雅黑,黑体,宋体;margin-top:8px;">题外话,补充说明</p>
                            <br>
                        </div>
                        
                        <hr/>
                        <div class="div-moduleblock">
                            <br>
                            <p style="color:#333;font-size:16px;font-family:微软雅黑,黑体,宋体">
                                <span style="color:#DEDDDE;font-size:96px;font-family:Arial;vertical-align: middle;line-height: 20px;">“</span>  <span style="margin-top:-20px;text-height:10px;">引用</span>
                            </p>
                            <br>
                        </div>
                        
                        <hr/>
                        <div class="div-moduleblock">
                            <br>
                            <fieldset style="border: 0px; margin: 0.8em 0px 0.5em;" >
                                <section style="display: inline-block; width: 4em; height: 4em; border-top-left-radius: 2em; border-top-right-radius: 2em; border-bottom-right-radius: 2em; border-bottom-left-radius: 2em; vertical-align: top; background-color: rgb(255, 185, 13); text-align: center;" >
                                    <section style="display: table; width: 100%; margin-top: -0.9em; " >
                                        <section style="display: table-cell; vertical-align: middle; line-height: 1.6em; font-size: 3.5em; font-family: 微软雅黑,黑体,宋体; text-align: inherit; text-decoration: inherit; color: rgb(255, 255, 255);" >
                                            1
                                        </section>
                                    </section>
                                </section>
                                <section style="display: inline-block; margin-left: 0.7em; padding-top: 0.3em;" >
                                    <section style="line-height: 1.4; font-size: 1.5em; font-family: 微软雅黑,黑体,宋体; text-align: inherit; text-decoration: inherit; color: rgb(42, 52, 58);" >
                                        有序标题
                                    </section>
                                    <section style="line-height: 1.4; margin-left: 0.2em; font-size: 1em; font-family: 微软雅黑,黑体,宋体; text-align: inherit; text-decoration: inherit; color: rgb(135, 139, 140);" >
                                        有序副标题
                                    </section>
                                </section>
                            </fieldset>
                            <br>
                        </div>
                        
                        <hr/>
                        <div class="div-moduleblock">
                            <br>
                            <p style="color:#333;font-size:16px;font-family:微软雅黑,黑体,宋体">
                                <span style="color:#fff;font-size:16px;font-family:微软雅黑,黑体,宋体;background-color:#ffb90d;border-radius:100%;padding:2px 8px;">1</span>  <span style="margin-top:-20px;text-height:10px;">有序列表内容</span>
                            </p>
                            <br>
                        </div>
                        
                        <hr/>
                        <div class="div-moduleblock">
                            <br>
                            <p style="color:#333;font-size:16px;font-family:微软雅黑,黑体,宋体">
                                <span style="color:#ffb90d;font-size:20px;font-family:微软雅黑,黑体,宋体;border-radius:100%;">●</span>  <span style="margin-top:-20px;text-height:10px;">无序列表内容</span>
                            </p>
                            <br>
                        </div>
                        
                    </div>
                </div>
                
                <!--正文栏-->
                <div class="col-md-8 col-sm-8 col-xs-8">
                    <!-- 加载编辑器的容器 -->
                    <script id="container" name="content" type="text/plain">
                        
                    </script>
                    <!-- 配置文件 -->
                    <script type="text/javascript" src="<?php echo PLUG_PATH.'php/ueditor1_4_3-utf8-php/ueditor.config.js'?>"></script>
                    <!-- 编辑器源码文件 -->
                    <script type="text/javascript" src="<?php echo PLUG_PATH.'php/ueditor1_4_3-utf8-php/ueditor.all.js'?>"></script>
                    <!-- 实例化编辑器 -->
                    <script type="text/javascript">
                        $(document).ready(function(){
                            var ue = UE.getEditor('container',{
                                toolbars:[['bold', 'italic','underline','|','forecolor','|','insertunorderedlist','insertorderedlist']],
                                enterTag:"<br>"   
                            });
                            ue.addListener("ready", function () {
                                    // editor准备好之后才可以使用
                                    ue.setContent($("#module-littletitle").html());
                                    ue.setHeight($(".div-moduleblock-container").height());
                            });
                            
                            $(".div-moduleblock").click(function(){
                                ue.setContent($(this).html());
                            });
                        });
                        
                        
                    </script>
                </div>
            </div>
            
            <div class="col-md-4 col-sm-4 col-xs-4">
            
            </div>
        </div>
    </div>


</body>

</html>