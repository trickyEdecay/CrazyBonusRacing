<?php 
require_once("../config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');


//验证在线
require_once("../php/CAQ_function.php");
$err = checkCAQlogin();
if($err->{'code'}!=0000){
    $tip = $err->{'info'};
    die("<script>window.location.href=\"CAQ_login.php?tip=$tip\"</script>");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>caq.less">
    <link rel="stylesheet/less" href="<?php echo constant("CSS_PATH")?>test.less">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <?php echo LESS_DEFINE; ?>
    <?php echo constant( 'JQuery_DEFINE');echo constant( 'BOOTSTRAP_DEFINE');?>
    <script>
    $(document).ready(function(){
        scanstate("idc");
    });
    function scanstate(what,appendix){
        appendix = appendix||"";
        $.ajax({
            url: "CAQ_mobile_parts.php",
            type: "POST",
            data:{
                'what':what,
                'appendix':appendix
            },
            success: function(data,status){
                $("#maincontainer").html(data);
            }
        });
    }
    </script>
</head>

<body id="maincontainer" style="background-color:#fafafa">
    
</body>

</html>