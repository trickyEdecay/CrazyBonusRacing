<?php

require_once ("inc/init.inc.php");


$klein = new \Klein\Klein();

$klein->respond('GET', '/test/helloworld', function () {
    die( 'Hello World!');
});

$klein->respond(function ($re) {
    return 'All the things'.$re->uri();
});

$klein->dispatch();