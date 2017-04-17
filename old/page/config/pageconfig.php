<?php
//系统总config文件引入
$mainconfig = str_replace('\\','/',dirname(dirname(dirname(__FILE__)))."/config/config.php");
require_once($mainconfig);
?>