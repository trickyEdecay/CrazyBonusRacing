<?php

require_once ("inc/init.inc.php");


#使用Klein 来作为路由
$klein = new \Klein\Klein();

# cbradmin/question
$klein->respond('GET', ROOT_PREFIX.ADMIN_PREFIX."/[:action]", function ($request) {
    require_once (PAGES."/admin/".$request->action.".php");
    die();
});

$klein->respond(array('POST','GET'),ROOT_PREFIX.'/api/[:action]', function ($request) {
    require_once (API."/".$request->action.".php");
    die();
});

# not found
$klein->respond(function ($re) {
    echo($re->params());
    die('could not found this uri: '.$re->uri());
});

$klein->dispatch();