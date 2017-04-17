<?php



#后台地址前缀
define("ADMIN_PREFIX","/cbradmin");


#根目录地址
define("ROOT",dirname(dirname(__FILE__)));

#php 插件目录
define("VENDOR","/vendor");

#页面目录
define("PAGES","/pages");

#config 目录
define("CONFIG","/config");

#读取 config.json
$config_json = json_decode(file_get_contents(ROOT.CONFIG."/config.json"),true);

#网站目录前缀
define("ROOT_PREFIX","/".$config_json["website_prefix"]);

#添加插件目录到搜索变量中
set_include_path(get_include_path().PATH_SEPARATOR.ROOT.VENDOR);

#注册自动加载类的方法，新建对象的时候会调用这个函数
spl_autoload_register(function ($class_name) {
    $class_name= str_replace("\\","/",$class_name).".php";
    require_once($class_name);
});



#数据库相关信息

#数据库地址
define("DB_HOST",$config_json["db_host"]);

#数据库用户
define("DB_USR",$config_json["db_usr"]);

#数据库密码
define("DB_PWD",$config_json["db_pwd"]);

#所使用的数据库名
define("DB_TABLE",$config_json["db_table"]);