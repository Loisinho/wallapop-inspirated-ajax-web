<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define ('DS', DIRECTORY_SEPARATOR);

$baseRoute = dirname(dirname(__FILE__)).DS;

$config['inc'] = $baseRoute.'inc'.DS;
$config['class'] = $baseRoute.'class'.DS;
$config['img'] = $baseRoute.'public/img'.DS;

define ('DB_SERVIDOR','localhost');
define ('DB_PUERTO','3306');
define ('DB_BASEDATOS','wallapush');
define ('DB_USUARIO','wallapush');
define ('DB_PASSWORD','abc123.');

require_once $config['class'].'database.php';
require_once $config['inc'].'functions.php';
