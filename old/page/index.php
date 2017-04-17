<?php 
require_once("config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');
?>

<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>test.less">
    <?php echo LESS_DEFINE; ?>
    <?php echo constant( 'JQuery_DEFINE');echo constant( 'BOOTSTRAP_DEFINE'); echo constant('COMMONCSSADJUSTJS_DEFINE');?>
</head>

<body>
    <div class='container mainContainer'>
        <div class="col-md-12 col-sm-12 col-xs-12 div_mainblock">
            <script type="text/javascript">
                    window.location.href = "activity/GraphicsDesign5th";
            </script>
        </div>
    </div>


</body>

</html>