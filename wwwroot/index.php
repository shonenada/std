<?php

/** 定义常量 **/
// 程序包所在的目录绝对路径
define ("DOCROOT", realpath(__DIR__ . "/../") . DIRECTORY_SEPARATOR);
// 依赖库路径，依赖库在 composer.json 中添加
define ("PKGROOT", realpath(DOCROOT . "vendor/") . DIRECTORY_SEPARATOR);
// 程序自身库
define ("APPROOT", realpath(DOCROOT . "std/") . DIRECTORY_SEPARATOR);
// web 根目录
define ("WEBROOT", realpath(DOCROOT . "wwwroot/") . DIRECTORY_SEPARATOR);

// 引入依赖库

require(PKGROOT . "autoload.php");
require_once(APPROOT . "app.php");

$configs = array(
    WEBROOT . 'development.conf.php',
    WEBROOT . 'custom.conf.php',
);

$app = create_app($configs);
$app->run();
