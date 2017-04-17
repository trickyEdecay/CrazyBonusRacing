<?php

//定义后台根目录
define('ROOT_PATH',str_replace('\\','/',dirname(dirname(__FILE__))));
//网页路径
define('PAGE_PATH','/page/');
//临时文件存放处
define('TEMP_DIR','/temp/');
//css路径
define('CSS_PATH',PAGE_PATH.'css/');
//upload路径
define('UPLOAD_PATH','/upload/');
//插件路径
define('PLUG_PATH','/plugin/');


//数据库相关
define('DB_HOSTNAME','localhost');
define('DB_USER','kkvip');
define('DB_PASSWORD','kkvipkkvip');
define('DB_DBNAME','667332');

//bootstrap声明
define('BOOTSTRAP_DEFINE','
<!-- Bootstrap -->
<link rel="stylesheet" href="'.CSS_PATH.'bootstrap.min.css">
<!-- <link rel="stylesheet" href="http://cdn.bootcss.com/twitter-bootstrap/3.0.3/css/bootstrap.min.css"> -->
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn\'t work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.min.js"></script>
<script src="http://cdn.bootcss.com/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="'.PLUG_PATH.'js/bootstrap.min.js"></script>
<!-- <script src="http://cdn.bootcss.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>-->

');

//jQuery声明
define('JQuery_DEFINE','
<!-- jQuery -->
<script src="'.PLUG_PATH.'js/jquery-1.9.1.js"></script>
<!-- <script src="http://lib.sinaapp.com/js/jquery/1.7.2/jquery.min.js"></script>-->
');

//所有页面的关于公用css的细节调整的js的引入
define('COMMONCSSADJUSTJS_DEFINE','<script src="js/segments/common/adjustCommonCss.js"></script>');

//LESS声明
define('LESS_DEFINE','<script src="'.PLUG_PATH.'js/less-1.7.5.min.js"></script>');




//秘钥
define('SKEY','ert1g16d5f1g65dSAssf');

//时区设置
date_default_timezone_set('PRC'); //设置中国时区


?>