<?php

#根目录地址
define("ROOT",__DIR__);

#php 插件目录
define("VENDOR","vendor");



set_include_path(get_include_path().PATH_SEPARATOR.VENDOR);

spl_autoload_register(function ($class_name) {
    $class_name= str_replace("\\","/",$class_name).".php";
    require_once($class_name);
});