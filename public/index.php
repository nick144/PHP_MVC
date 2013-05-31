<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(_FILE_)));

$url = $_GET["url"];


require_once(ROOT.DS.'libary'.DS.'bootstrap.php');

echo $url;