<?php

require_once ("inc/init.inc.php");


#使用Klein 来作为路由
$klein = new \Klein\Klein();

# cbradmin/question
$klein->respond('GET', ROOT_PREFIX.ADMIN_PREFIX."/question", function () {
    require_once (PAGES."/admin/question.php");
    die();
});

# not found
$klein->respond(function ($re) {
    die('could not found this uri: '.$re->uri());
});

$klein->dispatch();