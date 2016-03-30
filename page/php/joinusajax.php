<?php //用来处理报名表?>
<?php
require_once("../config/pageconfig.php");
require_once(ROOT_PATH.PLUG_PATH.'/php/sqlhelper.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/encrypt.php');
require_once(ROOT_PATH.PLUG_PATH.'/php/checkString.php');
?>

<?php
//判断操作数,分配到函数进行操作
header('Content-Type:text/html; charset=utf-8');//使用gb2312编码，使中文不会变成乱码
$what = $_POST['what'];
switch($what){
    case 'submit':
        submitRegisterForm(
            $_POST['registertype'],
            $_POST['name'],
            $_POST['sex'],
            $_POST['longtel'],
            $_POST['shorttel'],
                                
            $_POST['address'],
            $_POST['fromwhere'],
            $_POST['qq'],
            $_POST['birthday'],
            $_POST['college'],
                                
            $_POST['knowusfrom'],
            $_POST['whatwillyoulearn'],
            $_POST['politicalstatus'],
            $_POST['duty'],
            $_POST['department'],
                                
            $_POST['obeydistribute'],
            $_POST['introduce'],
            $_POST['departmentyouknow'],
            $_POST['honor'],
            $_POST['handinmoneytype']
        );
        break;
}
?>

<?php
function submitRegisterForm(
$registertype,
$name,
$sex,
$longtel,
$shorttel,

$address,
$fromwhere,
$qq,
$birthday,
$college,

$knowusfrom,
$whatwillyoulearn,
$politicalstatus,
$duty,
$department,

$obeydistribute,
$introduce,
$departmentyouknow,
$honor,
$handinmoneytype
){
    
    //--------------验证是否没填-------------------
    if($registertype==''){
        die("error:::001:::不要私自更改页面内容然后提交报名表哟~");   
    }
    if($name==''){
        die("error:::002:::姓名不能为空~");   
    }
    if($sex==''){
        die("error:::003:::性别这么简单就选一个吧TAT~");   
    }
    if($longtel==''){
        die("error:::004:::长号很重要~必须要填哦");   
    }
    if($shorttel==''){
        die("error:::005:::短号很重要~必须要填哦");   
    }
    
    if($address==''){
        die("error:::006:::学校住址不能为空哦~");   
    }
    if($fromwhere==''){
        die("error:::007:::籍贯不能为空噢~");   
    }
    if($qq==''){
        die("error:::008:::qq不能为空噢~");   
    }
    if($birthday==''){
        die("error:::009:::生日必须要填哦");   
    }
    if($college==''){
        die("error:::010:::学院和班级必须要填哦");   
    }
    
    if($registertype=="会员"){
        if($knowusfrom==''){
            die("error:::011:::至少选一个了解科创社的渠道嘛~");   
        }
    }
    if($registertype=="干事"){
        if($politicalstatus==''){
            die("error:::012:::政治面貌必须要填噢~");   
        }
        if($department==''){
            die("error:::013:::竞选部门必须选至少一个~可以多选哟");   
        }
        if($obeydistribute==''){
            die("error:::014:::是否服从分配必须选一个~");   
        }
        if($introduce==''){
            die("error:::015:::自我介绍必须写噢~对于竞选的你有好处");   
        }
    }
    if($handinmoneytype==''){
        die("error:::016:::要以何种形式上交会费和照片是必选项噢~你好像忘记了呢");   
    }

    
    //--------------验证是否没填-------------------完
    //--------------验证是否格式正确----------------
    if(!is_name($name)){
        die("error:::020:::姓名格式错误,请重新填写");   
    }
    if(!is_sex($sex)){
        die("error:::001:::不要私自更改页面内容然后提交报名表哟~");   
    }
    if(!is_longtel($longtel)){
        die("error:::021:::长号格式不对哦~修改一下吧?");   
    }
    if(!is_shorttel($shorttel)){
        die("error:::022:::短号格式不对哦~修改一下吧?");   
    }
    if(!is_address($address)){
        die("error:::023:::学校住址信息不对噢,最好按照提示中的格式填写");   
    }
    if(!is_number($qq)){
        die("error:::024:::请输入纯数字的qq号");   
    }
    if(!is_college($college)){
        die("error:::025:::请输入正确的学院班级,最好按照提示中的格式填写");   
    }
    //--------------验证是否格式正确----------------完
    $nc = new sqlhelper();
    $opecode = $nc->connect();
    if($opecode!=0){
        die("error:::100:::网络连接错误");
    }
    $now = date("Y-m-d H:i:s");
    $handinmoneykey = "KCS-".substr(md5(uniqid()),0,8);
    $result = $nc ->mysql("insert into registration_info(
                            registertype,
                            name,
                            sex,
                            longtel,
                            shorttel,

                            address,
                            fromwhere,
                            qq,
                            birthday,
                            college,

                            knowusfrom,
                            whatwillyoulearn,
                            politicalstatus,
                            duty,
                            department,

                            obeydistribute,
                            introduce,
                            departmentyouknow,
                            honor,
                            handinmoneytype,
                            submittime,
                            formstatus,
                            handinmoneykey
                            )
                            values
                            (
                            '$registertype',
                            '$name',
                            '$sex',
                            '$longtel',
                            '$shorttel',

                            '$address',
                            '$fromwhere',
                            '$qq',
                            '$birthday',
                            '$college',

                            '$knowusfrom',
                            '$whatwillyoulearn',
                            '$politicalstatus',
                            '$duty',
                            '$department',

                            '$obeydistribute',
                            '$introduce',
                            '$departmentyouknow',
                            '$honor',
                            '$handinmoneytype',
                            '$now',
                            '待收款',
                            '$handinmoneykey'
                            )");
    if(!$result){
        die("error:::050:::提交错误");
    }
    setcookie('ukey', "$name:::$now:::$handinmoneykey",24*3600*10);
    echo "success:::submitOk:::$handinmoneykey";
}

?>
