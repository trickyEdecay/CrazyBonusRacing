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
    <?php echo LESS_DEFINE; ?>
    <?php echo constant( 'JQuery_DEFINE');echo constant( 'BOOTSTRAP_DEFINE');?>
    
    
    <script>
    $(document).ready(function(){
        setInterval("scanstate()",2000);
    });
    function scanstate(){
        $.ajax({
            url: "CAQ_projector_parts.php",
            type: "POST",
            data:{
                'what':'scanstate'
            },
            success: function(data,status){
                $("#maincontainer").html(data);
            }
        });
    }
    </script>
</head>
<body id="maincontainer" style="background-color:#ddd">
    
</body>

</html>