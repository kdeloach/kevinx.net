<?php

require_once '/var/www/kevinx.net/local_settings.php';
require BASE_PATH . '/html/templetrade/kwork_im/AppMain.php';

$app_path = BASE_PATH . '/html/templetrade/app_tt';
$includepaths = array(
    get_include_path(),
    $app_path
);
set_include_path(implode(PATH_SEPARATOR, $includepaths));

AppMain::handleRequest();
